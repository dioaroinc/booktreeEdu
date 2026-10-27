<?php

namespace App\Libraries;

/**
 * 책나무 본사 user 테이블 비밀번호 검증 (솔트 + SHA-256)
 *
 * 형식은 기존 booktree 사이트 / booktree-order 와 반드시 동일해야 함:
 *   "sha256$<솔트 16자리 hex>$<sha256(솔트 + 정규화한 비밀번호)>"  (88자)
 * 예전 로그인이 MySQL 비교(utf8_general_ci: 대소문자/끝 공백 무시)였어서 같은 규칙으로 정규화한다.
 * 아직 평문으로 남아 있는 계정도 검증된다. (이 사이트는 본사 DB 를 읽기만 하고 고치지 않는다)
 */
class PasswordHash
{
    public static function normalize(string $plain): string
    {
        return strtolower(rtrim($plain, ' '));
    }

    public static function isHashed(?string $stored): bool
    {
        return (bool) preg_match('/^sha256\$[0-9a-f]{16}\$[0-9a-f]{64}$/', (string) $stored);
    }

    public static function hash(string $plain): string
    {
        $salt = bin2hex(random_bytes(8));

        return 'sha256$' . $salt . '$' . hash('sha256', $salt . self::normalize($plain));
    }

    public static function verify(string $plain, ?string $stored): bool
    {
        $stored = (string) $stored;
        if ($stored === '' || $plain === '') {
            return false;
        }
        if (self::isHashed($stored)) {
            [, $salt, $hash] = explode('$', $stored);

            return hash_equals($hash, hash('sha256', $salt . self::normalize($plain)));
        }

        return hash_equals(self::normalize($stored), self::normalize($plain));
    }
}
