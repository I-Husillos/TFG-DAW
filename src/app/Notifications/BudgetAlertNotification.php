<?php

namespace App\Notifications;

use App\Models\Budget;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notificación enviada cuando un presupuesto alcanza o supera su umbral de alerta.
 */
class BudgetAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * El presupuesto que ha disparado la alerta.
     * Se inyecta en el constructor (Dependency Injection).
     */
    public Budget $budget;

    /**
     * El importe ya gastado en el momento de la alerta.
     */
    public float $spent;

    /**
     * El porcentaje consumido del presupuesto (0-100).
     */
    public float $percentage;

    public function __construct(Budget $budget, float $spent, float $percentage)
    {
        $this->budget     = $budget;
        $this->spent      = $spent;
        $this->percentage = $percentage;
    }

    /**
     * Define los canales por los que se enviará la notificación.
     * Solo 'mail' en este caso, pero podrías añadir 'database' para
     * guardarla también en la base de datos como en el proyecto de tickets.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Construye el mensaje de email.
     *
     * MailMessage es el builder de emails de Laravel. Permite construir
     * el contenido del email de forma fluida (encadenando métodos).
     * Laravel usa su plantilla por defecto (que puedes publicar y
     * personalizar con: php artisan vendor:publish --tag=laravel-notifications)
     */
    public function toMail(object $notifiable): MailMessage
    {
        $categoryName = $this->budget->category->display_name
                        ?? $this->budget->category->name;

        $exceeded = $this->percentage >= 100;

        // El asunto del email cambia según si se ha excedido o solo alertado
        $subject = $exceeded
            ? "⚠️ Presupuesto superado: {$categoryName}"
            : "🔔 Alerta de presupuesto: {$categoryName}";

        $spentFormatted = number_format($this->spent, 2, ',', '.');
        $limitFormatted = number_format($this->budget->limit_amount, 2, ',', '.');
        $percentageFormatted = number_format($this->percentage, 1);

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hola, {$notifiable->username}")
            ->line(
                $exceeded
                    ? "Has **superado** el límite de presupuesto para la categoría **{$categoryName}** este mes."
                    : "Has alcanzado el **{$percentageFormatted}%** de tu presupuesto para **{$categoryName}** este mes."
            )
            ->line("- **Gastado:** {$spentFormatted} €")
            ->line("- **Límite:** {$limitFormatted} €")
            ->line("- **Porcentaje consumido:** {$percentageFormatted}%")
            ->action('Ver mis presupuestos', route('budgets.index'))
            ->line(
                $exceeded
                    ? 'Te recomendamos revisar tus gastos para no exceder más tu presupuesto.'
                    : 'Lleva un control de tus gastos para no superar el límite establecido.'
            );
    }
}