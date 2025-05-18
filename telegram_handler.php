<?php
// ----- НАСТРОЙКИ -----
$botToken = "8056615682:AAGgIB9Ed3uO2FWaLar7R_m3ucmHCv1mMo8"; // ВАШ API ТОКЕН БОТА
$chatId = "942071644"; // ВАШ CHAT ID (или ID группы)
$siteUrl = "/burbro_site/"; // ПУТЬ К КОРНЕВОЙ ПАПКЕ ВАШЕГО САЙТА НА ЛОКАЛЬНОМ СЕРВЕРЕ

// Функция для вывода HTML-страницы ответа
function display_response_page($title, $message_text, $is_success = true, $site_url_param = "/") {
    $primaryButtonGradient = "linear-gradient(to right, #38BDF8, #A78BFA, #38BDF8)"; 
    $errorButtonGradient = "linear-gradient(to right, #F87171, #F97316, #F87171)"; 
    $wrapperGradient = $is_success ? $primaryButtonGradient : $errorButtonGradient;

    echo "<!DOCTYPE html><html lang='ru'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1.0'><title>" . htmlspecialchars($title) . "</title>";
    echo "<script src='https://cdn.tailwindcss.com'></script>"; 
    echo "<link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap' rel='stylesheet'>";
    echo "<style>
            body { 
                font-family: 'Inter', sans-serif; 
                background-color: #F9FAFB; 
                color: #1F2937; 
                display: flex; 
                flex-direction: column; 
                justify-content: center; 
                align-items: center; 
                min-height: 100vh; 
                margin: 0; 
                padding: 20px; 
                box-sizing: border-box;
                position: relative;
                overflow: hidden; 
            }
            #background-canvas-response {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0; 
                opacity: 0.3; /* Яркость фона (0.0 - 1.0) */
                pointer-events: none; 
            }
            .message-wrapper { 
                position: relative;
                z-index: 1;
                max-width: 500px;
                width: 100%;
            }
            .gradient-border-wrapper {
                padding: 3px; 
                border-radius: 1rem; 
                background-image: " . $wrapperGradient . ";
                background-size: 200% auto;
                animation: gradient-flow 4s linear infinite;
                box-shadow: 0 4px 10px rgba(0,0,0,0.07);
            }
            .content-inside-border {
                background-color: #FFFFFF; 
                padding: 2rem; 
                border-radius: calc(1rem - 3px); 
                text-align: center; 
            }
            .content-inside-border h2 {
                font-size: 2rem; 
                font-weight: 700; 
                margin-bottom: 1.5rem; 
                color: " . ($is_success ? '#10B981' : '#EF4444') . "; 
            }
            .content-inside-border p {
                font-size: 1.125rem; 
                line-height: 1.75rem; 
                margin-bottom: 2rem; 
                color: #374151; 
            }
            .btn-response { 
                padding: 0.75rem 2rem; 
                border-radius: 9999px; 
                font-weight: 600; 
                text-decoration: none; 
                display: inline-block; 
                margin-top: 1rem;
                margin-right: 0.5rem;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                color: white;
                background-size: 200% auto;
                animation: gradient-flow 4s linear infinite;
                box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            }
            .btn-response:hover { 
                transform: scale(1.05); 
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            }
            .btn-primary-response {
                background-image: " . $primaryButtonGradient . ";
            }
            .btn-secondary-response {
                background-color: #E5E7EB; 
                color: #374151; 
                background-image: none; 
                animation: none; 
            }
            .btn-secondary-response:hover {
                background-color: #D1D5DB; 
            }
            @keyframes gradient-flow {
                0% { background-position: 0% center; }
                50% { background-position: 100% center; }
                100% { background-position: 0% center; }
            }
          </style>";
    echo "</head><body>";
    echo "<canvas id='background-canvas-response'></canvas>"; 
    echo "<div class='message-wrapper'>"; 
    echo "<div class='gradient-border-wrapper'>";
    echo "<div class='content-inside-border'>";
    echo "<h2>" . htmlspecialchars($title) . "</h2>";
    echo "<p>" . $message_text . "</p>";
    echo "<div>";
    echo "<a href='javascript:history.back()' class='btn-response btn-primary-response'>Вернуться назад</a>";
    echo "<a href='" . htmlspecialchars($site_url_param) . "' class='btn-response btn-secondary-response'>На главную</a>";
    echo "</div>";
    echo "</div>"; 
    echo "</div>"; 
    echo "</div>"; 
    echo "<script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('background-canvas-response');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let animationFrameId;

            const numStripes = 40; // Увеличено количество полос
            const stripeColors = ['#38BDF8', '#60A5FA', '#8B5CF6', '#A78BFA', '#C4B5FD', '#6366F1', '#4F46E5', '#7C3AED']; 
            let stripes = [];

            function resizeCanvas() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
                initializeStripes();
                canvas.style.opacity = 0.3; // Яркость фона (0.0 - 1.0)
            }

            function Stripe(x, y, angle, speed, color, lineWidth, amplitude, frequency, length) {
                this.x = x;
                this.y = y;
                this.angle = angle; 
                this.speed = speed;
                this.color = color;
                this.lineWidth = lineWidth;
                this.amplitude = amplitude;
                this.frequency = frequency;
                this.phase = Math.random() * Math.PI * 2;
                this.time = Math.random() * 100; 
                this.length = length; 
            }

            Stripe.prototype.update = function() {
                this.x += Math.cos(this.angle) * this.speed;
                this.y += Math.sin(this.angle) * this.speed;
                this.time += 0.02; 

                const margin = this.length * 0.6; 
                if (this.x < -margin) this.x = canvas.width + margin * 0.95;
                if (this.x > canvas.width + margin) this.x = -margin * 0.95;
                if (this.y < -margin) this.y = canvas.height + margin * 0.95;
                if (this.y > canvas.height + margin) this.y = -margin * 0.95;
            };

            Stripe.prototype.draw = function(ctx) {
                ctx.save();
                ctx.beginPath();
                ctx.strokeStyle = this.color;
                ctx.lineWidth = this.lineWidth;
                ctx.globalAlpha = 0.7; // Прозрачность самих полос
                
                ctx.shadowBlur = 12; // Увеличен размер свечения
                ctx.shadowColor = this.color; 

                ctx.translate(this.x, this.y);
                ctx.rotate(this.angle);

                for (let i = -this.length / 2; i < this.length / 2; i++) {
                    const waveX = i;
                    const waveY = this.amplitude * Math.sin(i * this.frequency + this.time + this.phase);
                    if (i === -this.length / 2) {
                        ctx.moveTo(waveX, waveY);
                    } else {
                        ctx.lineTo(waveX, waveY);
                    }
                }
                ctx.stroke();
                ctx.shadowBlur = 0; 
                ctx.restore();
            };

            function initializeStripes() {
                stripes = [];
                const baseLength = Math.max(canvas.width, canvas.height) * 0.5; // Длина полос может быть чуть короче

                for (let i = 0; i < numStripes; i++) {
                    const x = Math.random() * canvas.width;
                    const y = Math.random() * canvas.height;
                    const angle = Math.random() * Math.PI * 2; 
                    const speed = Math.random() * 0.3 + 0.1; // Немного уменьшена скорость для большего количества полос
                    const color = stripeColors[i % stripeColors.length];
                    const lineWidth = Math.random() * 1 + 0.5; // Тонкие полосы
                    const amplitude = Math.random() * 15 + 5; // Меньшая амплитуда
                    const frequency = Math.random() * 0.03 + 0.01; 
                    const length = baseLength * (Math.random() * 0.2 + 0.8); 
                    stripes.push(new Stripe(x, y, angle, speed, color, lineWidth, amplitude, frequency, length));
                }
            }
            
            function animateBackground() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                stripes.forEach(stripe => {
                    stripe.update();
                    stripe.draw(ctx);
                });
                animationFrameId = requestAnimationFrame(animateBackground);
            }

            resizeCanvas(); 
            animateBackground(); 

            window.addEventListener('resize', () => {
                cancelAnimationFrame(animationFrameId);
                resizeCanvas(); 
                animateBackground(); 
            });
        });
    </script>";
    echo "</body></html>";
}

// ----- PHP-код обработки формы (остается без изменений) -----
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['request-name']) ? htmlspecialchars(trim($_POST['request-name'])) : 'Не указано';
    $phone = isset($_POST['request-phone']) ? htmlspecialchars(trim($_POST['request-phone'])) : 'Не указан';
    $telegram_user = isset($_POST['request-telegram']) ? htmlspecialchars(trim($_POST['request-telegram'])) : '';

    $message_tg = "<b>🔔 Новая заявка с сайта БурБРО!</b>\n\n";
    $message_tg .= "<b>👤 Имя:</b> " . $name . "\n";
    $message_tg .= "<b>📞 Телефон:</b> " . $phone . "\n";
    if (!empty($telegram_user)) {
        $telegram_user_link = (strpos($telegram_user, '@') !== 0) ? '@' . $telegram_user : $telegram_user;
        $message_tg .= "<b>💬 Телеграм:</b> " . $telegram_user_link . "\n";
    }
    $message_tg .= "\n<i>📅 Дата: " . date('d.m.Y H:i:s') . "</i>";

    $telegramApiUrl = "https://api.telegram.org/bot" . $botToken . "/sendMessage";
    $params = [
        'chat_id' => $chatId,
        'text' => $message_tg,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $telegramApiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($httpcode == 200 && $response) {
        $responseData = json_decode($response, true);
        if ($responseData && $responseData['ok']) {
            display_response_page("Спасибо!", "Ваша заявка успешно отправлена.<br>Мы свяжемся с вами в ближайшее время.", true, $siteUrl);
        } else {
            $error_message = "Не удалось отправить заявку. Ответ от Telegram API: " . ($responseData ? htmlspecialchars($responseData['description']) : 'Неизвестная ошибка от API');
            display_response_page("Ошибка!", $error_message, false, $siteUrl);
        }
    } else {
        $error_message = "Не удалось связаться с сервером Telegram.";
        if ($curl_error) {
            $error_message .= " Ошибка cURL: " . htmlspecialchars($curl_error);
        }
        $error_message .= " Код ответа HTTP: " . $httpcode;
        display_response_page("Ошибка!", $error_message, false, $siteUrl);
    }

} else {
    header("HTTP/1.1 403 Forbidden");
    display_response_page("Доступ запрещен", "Этот скрипт предназначен для обработки данных формы.", false, $siteUrl);
}
?>
