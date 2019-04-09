<?php
namespace Yii\I18n;

/**
 * GettextPoFile represents a PO Gettext message file.
 */
final class GettextPoFile implements MessageWriter, MessageReader
{
    private $path;

    private $fileMtime;
    private $messages;

    public function __construct(string $path)
    {
        $this->path = $path;
    }


    /**
     * Loads messages from a PO file.
     * @param string $filePath file path
     * @param string $context message context
     * @return array message translations. Array keys are source messages and array values are translated messages:
     * source message => translated message.
     */
    public function all($context = null): array
    {
        if (!is_file($this->path)) {
            throw new \RuntimeException('Invalid path provided: ' . $this->path);
        }

        $mtime = filemtime($this->path);
        if ($this->fileMtime !== $mtime) {
            $pattern = '/(msgctxt\s+"(.*?(?<!\\\\))")?\s+' // context
              . 'msgid\s+((?:".*(?<!\\\\)"\s*)+)\s+' // message ID, i.e. original string
              . 'msgstr\s+((?:".*(?<!\\\\)"\s*)+)/'; // translated string
            $content = file_get_contents($this->path);
            $matches = [];
            $matchCount = preg_match_all($pattern, $content, $matches);

            $messages = [];
            for ($i = 0; $i < $matchCount; ++$i) {
                if ($matches[2][$i] === $context) {
                    $id = $this->decode($matches[3][$i]);
                    $messages[$id] = $this->decode($matches[4][$i]);
                }
            }

            $this->messages = $messages;
        }

        return $this->messages;
    }

    /**
     * Saves messages to a PO file.
     * @param array $messages message translations. Array keys are source messages and array values are
     * translated messages: source message => translated message. Note if the message has a context,
     * the message ID must be prefixed with the context with chr(4) as the separator.
     */
    public function write(array $messages): void
    {
        $language = str_replace('-', '_', basename(dirname($this->path)));
        $headers = [
            'msgid ""',
            'msgstr ""',
            '"Project-Id-Version: \n"',
            '"POT-Creation-Date: \n"',
            '"PO-Revision-Date: \n"',
            '"Last-Translator: \n"',
            '"Language-Team: \n"',
            '"Language: ' . $language . '\n"',
            '"MIME-Version: 1.0\n"',
            '"Content-Type: text/plain; charset=' . ini_get('default_charset') . '\n"',
            '"Content-Transfer-Encoding: 8bit\n"',
        ];
        $content = implode("\n", $headers) . "\n\n";
        foreach ($messages as $id => $message) {
            $separatorPosition = strpos($id, chr(4));
            if ($separatorPosition !== false) {
                $content .= 'msgctxt "' . substr($id, 0, $separatorPosition) . "\"\n";
                $id = substr($id, $separatorPosition + 1);
            }
            $content .= 'msgid "' . $this->encode($id) . "\"\n";
            $content .= 'msgstr "' . $this->encode($message) . "\"\n\n";
        }
        file_put_contents($this->path, $content);
    }

    /**
     * Encodes special characters in a message.
     * @param string $string message to be encoded
     * @return string the encoded message
     */
    private function encode($string)
    {
        return str_replace(
            ['"', "\n", "\t", "\r"],
            ['\\"', '\\n', '\\t', '\\r'],
            $string
        );
    }

    /**
     * Decodes special characters in a message.
     * @param string $string message to be decoded
     * @return string the decoded message
     */
    private function decode($string)
    {
        $string = preg_replace(
            ['/"\s+"/', '/\\\\n/', '/\\\\r/', '/\\\\t/', '/\\\\"/'],
            ['', "\n", "\r", "\t", '"'],
            $string
        );

        return substr(rtrim($string), 1, -1);
    }

    public function one(string $id, $context = null): ?string
    {
        $messages = $this->all($context);
        return $messages[$id] ?? null;
    }

    public function plural(string $id, int $count, $context = null): ?string
    {
        // TODO: implement. Should be supported natively before release.
    }
}
