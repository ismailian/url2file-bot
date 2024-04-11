<?php

namespace TeleBot\App\Handlers;

use Exception;
use TeleBot\System\BaseEvent;
use TeleBot\System\Events\Url;
use TeleBot\System\Events\Command;
use TeleBot\System\Types\IncomingUrl;
use TeleBot\System\Events\CallbackQuery;
use TeleBot\System\Types\InlineKeyboard;
use TeleBot\System\Types\IncomingCallbackQuery;

class Urls extends BaseEvent
{

    /**
     * handle start command
     *
     * @return void
     * @throws Exception
     */
    #[Command('start')]
    public function welcome(): void
    {
        $name = $this->event['message']['from']['first_name'];
        $message = "Welcome aboard, $name!\n\n";
        $message .= "send a url and convert it to Image or PDF document!";

        $this->telegram->sendMessage($message);
    }

    /**
     * handle incoming urls
     *
     * @param IncomingUrl $url
     * @return void
     * @throws Exception
     */
    #[Url]
    public function onUrl(IncomingUrl $url): void
    {
        if (preg_match('/(localhost|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/', $url->getUrl())) {
            $this->telegram->sendMessage('Please provide a valid url!');
            return;
        }

        $this->telegram
            ->withOptions([
                'reply_markup' => [
                    'inline_keyboard' => (new InlineKeyboard())
                        ->addButton('🖼️ Image', ['type:image' => $url->getUrl()], InlineKeyboard::CALLBACK_DATA)
                        ->addButton('🗎 PDF', ['type:pdf' => $url->getUrl()], InlineKeyboard::CALLBACK_DATA)
                        ->toArray()
                ]
            ])
            ->sendMessage('Would you like to generate an Image or PDF?');
    }

    /**
     * generate image
     *
     * @param IncomingCallbackQuery $query
     * @return void
     * @throws Exception
     */
    #[CallbackQuery('type:image')]
    public function toImage(IncomingCallbackQuery $query): void
    {
        if (empty($query('type:image'))) return;

        $this->telegram->deleteMessage($query->messageId);
        $this->telegram->sendMessage(('Hold on! we are processing your url..'));

        $url = $query('type:image');
        $bin = getenv('WK_IMG_BIN', true);
        $filePath = 'tmp/' . md5(microtime(true)) . '.png';
        $args = ["\"$bin\"", '--quality 100', '--format PNG', "\"{$url}\"", "\"$filePath\""];
        $result = system(join(' ', $args));
        if ((!is_string($result) && !$result) || !file_exists($filePath)) {
            $this->telegram->editMessage(
                $this->telegram->getLastMessageId(),
                "We failed to generate your image! Please try again later."
            );
            return;
        }

        $this->telegram->deleteLastMessage();
        $this->telegram->sendPhoto($filePath);

        @unlink($filePath);
    }

    /** generate pdf
     *
     * @param IncomingCallbackQuery $query
     * @return void
     * @throws Exception
     */
    #[CallbackQuery('type:pdf')]
    public function toPdf(IncomingCallbackQuery $query): void
    {
        if (empty($query('type:pdf'))) return;

        $this->telegram->deleteMessage($query->messageId);
        $this->telegram->sendMessage(('Hold on! we are processing your url..'));

        $url = $query('type:pdf');
        $bin = getenv('WK_PDF_BIN', true);
        $filePath = 'tmp/' . md5(microtime(true)) . '.pdf';
        $args = ["\"$bin\"", "\"{$url}\"", "\"$filePath\""];
        $result = system(join(' ', $args));
        if ((!is_string($result) && !$result) || !file_exists($filePath)) {
            $this->telegram->editMessage(
                $this->telegram->getLastMessageId(),
                "We failed to generate your image! Please try again later."
            );
            return;
        }

        $this->telegram->deleteLastMessage();
        $this->telegram->sendDocument($filePath);

        @unlink($filePath);
    }

}