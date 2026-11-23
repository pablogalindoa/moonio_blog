<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Storage encryption, NOT end-to-end encryption. The server holds the key.
class MessageCipher {
    private function key() {
        $key = base64_decode((string)getenv('MESSAGE_ENCRYPTION_KEY'), true);
        if ($key === false || strlen($key) !== 32) {
            throw new RuntimeException('El cifrado no está configurado en el servidor.');
        }
        return $key;
    }
    public function encrypt($message) {
        $iv = random_bytes(12);
        $ciphertext = openssl_encrypt($message, 'aes-256-gcm', $this->key(), OPENSSL_RAW_DATA, $iv, $tag);
        if ($ciphertext === false) throw new RuntimeException('No se pudo cifrar el mensaje.');
        return 'v1:' . base64_encode($iv . $tag . $ciphertext);
    }
    public function decrypt($message) {
        $raw = base64_decode(substr($message, 3), true);
        if (substr($message, 0, 3) !== 'v1:' || $raw === false || strlen($raw) < 28) {
            throw new RuntimeException('Mensaje cifrado inválido.');
        }
        $plain = openssl_decrypt(substr($raw, 28), 'aes-256-gcm', $this->key(), OPENSSL_RAW_DATA, substr($raw, 0, 12), substr($raw, 12, 16));
        if ($plain === false) throw new RuntimeException('No se pudo descifrar el mensaje.');
        return $plain;
    }
}
