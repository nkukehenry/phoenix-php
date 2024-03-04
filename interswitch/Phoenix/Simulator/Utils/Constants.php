<?php

namespace Interswitch\Phoenix\Simulator\Utils;

class Constants
{

 public const PRIKEY = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDZcVYmxRqprwsM
QOqNEWGoMGNm0nn4nvri9F3f63qTGQZ+KV4GWIbdEcd5GWlN6SA3XRdr2TLJzYR7
6O0MbWV7h+4xWFFoiS3m35oOOEfWB6zb7zgUlKPPLSguc0zApTST+eQRSQts9qbC
WRSdvSSMmpM7puM3C8IyPY2Htq4GlZ3e6vGfCGgZbTmKz4Z44Cn1WGfO7QHhBQnl
AQ8KCDSR3Ao75NdL5CLPNZnLF1BAcC9j6RXqBHZgH3P36PMXqRQjFn+pEj4tjtMd
eux4pgHzy4guYJ8M2SpfcQ+kbQxG0Bm6zoK7d3lZaxTt6kp+ID81w5UFpNdHjfxM
wY3jcENdAgMBAAECggEBAJfhu4yPJuWZV/0yJuNsXatOSVBb+kh4O8RlbmDnKZIv
68IaAQwrr+Ag+BUVc+Gw0gj55E83wmsFO+IVO+bvTvBvbShYiVycXUyjqQb/tuXA
LLTfLmvpu1YOM7/mV5lEjCpEZVLRk8KDss2TzXu0zx/LJMaI9rLPatEO/5npNztu
4nIIyMELT37RcbWJjC/bWUwPicMi9irOZEPeqXVwJ5hjVy+oiAFZFhtmgCQPoX7r
unvsX48+EnlXOIokdqFjcGJ7dwbtSxRok8e5u+jG4hopz0dy7NlHQkX9RaljgBQZ
KLfDWawIW13WXLN8CyoKFECCOn30u1Ol9VB84im9EiECgYEA+GaDU64IQc9SArmC
07lrIm2uNg1umIc/yidhTFukog1ko99pXJylpHEbQ+91fj+Pr2d3UbIVRjxQSABw
4GJv5390UM6M7HPPNvFrzB/qJ9h/bdKVHOiF3y+L67QjprbSFf6vGIOf7U22hJAB
VBiO99rOH0ocpbXXwTiot0RS9VUCgYEA4BhcXzly5q/tGMrfYYXMGHM1gX45qGIS
baWMrAmNOUtefQhWDqiBPbkgNE0DIokTrE6oc37Rs4eBYhTj59aDmptvnrQcYn+m
1cEuZSVrdRS6Do09vb5pAXCOXb4paV4UUstFnog/kVXBPovdFKxTa5zYTpz5ahD8
bQbUtu6TFekCgYBZsxslcqkEqssgtMDrl/96FUVdu4f+iIiehY37NvTCmDeJfbnc
eazPLxD7fVNeXbGTU7egxsAr9se+2HnqbWGfpUTpkU0ObducKZ4VvkieTu3lSLAU
GluNduv+F7TwFQDUdH3iSlXMKc4JHL9+EMg/9MGORwMFTB83ZTB3zbJvaQKBgQC5
zhkQ725Arc+1xchPcQOXpjbQBpvB7IcBGMm5fzX0MnTG0Nmhz79RMSTtmIkn4mZI
cPOkx5sR8yGo9E8/VBLMZ2K2QPpkBRmMlF9miA9ABY57MErn/2/LPMseGOT3M4VA
XMB9wNSMKYFP3eHSGUwVpveGvwriEfFqaMaIYunbuQKBgFlLwn2K2LltdmQef7/A
tioIXlsUp07RZFAdQZ5QaGDO8E9/xlsam9NSAJcwjzm59p5FgA35f6seTH72iu58
QPUavtKgihHIOyDE/J/QeoSGlkQe6nTqhe6B/rPU6f8CDTpdtMi24tSjZzPKMhnQ
HOU3BH4DtA/oBpRww6kW6BB0
-----END PRIVATE KEY-----
EOD;

public const PUBKEY = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA2XFWJsUaqa8LDEDqjRFh
qDBjZtJ5+J764vRd3+t6kxkGfileBliG3RHHeRlpTekgN10Xa9kyyc2Ee+jtDG1l
e4fuMVhRaIkt5t+aDjhH1ges2+84FJSjzy0oLnNMwKU0k/nkEUkLbPamwlkUnb0k
jJqTO6bjNwvCMj2Nh7auBpWd3urxnwhoGW05is+GeOAp9Vhnzu0B4QUJ5QEPCgg0
kdwKO+TXS+QizzWZyxdQQHAvY+kV6gR2YB9z9+jzF6kUIxZ/qRI+LY7THXrseKYB
88uILmCfDNkqX3EPpG0MRtAZus6Cu3d5WWsU7epKfiA/NcOVBaTXR438TMGN43BD
XQIDAQAB
-----END PUBLIC KEY-----
EOD;

    public  const MY_TERMINAL_ID = "3ISO0140";
    public const MY_SERIAL_ID = "equity.java.app";
    public const ACCOUNT_PWD = "1234";
    public const PHONE_NUMBER = "0779537749";

    //header strings
    public const TIMESTAMP = "Timestamp";
    public const TERMINAL_ID = "3ISO0140";
    public const NONCE = "Nonce";
    public const SIGNATURE = "Signature";
    public const AUTHORIZATION = "Authorization";
    public const AUTHORIZATION_REALM = "InterswitchAuth";
    public const ISO_8859_1 = "ISO-8859-1";
    public const AUTH_TOKEN = "AuthToken";
    public const APP_VERSION = "v1";

    private const SANDBOX_ROUTE = "https://dev.interswitch.io/api/v1/phoenix/";
    public const ROOT_LINK = self::SANDBOX_ROUTE;

    public const CLIENT_ID = "IKIADA097C504F4E38345C6E30FA1D47228EA13B8139";
    public const CLIENT_SECRET = "ZBxQp5CKY4upWzv0RJYSBCmhuIoKH8Ek6LNpBsE2sUU=";

    public const POST_REQUEST = "POST";
    public const GET_REQUEST = "GET";

    public const AES_CBC_PKCS7_PADDING = "AES/CBC/PKCS7Padding";
    public const RSA_NONE_OAEPWithSHA256AndMGF1Padding = "RSA/NONE/OAEPWithSHA256AndMGF1Padding";

    public static $sessionKey = "";
    public static $authToken = "";

    public static function setSessionKey($sessKey) {
        self::$sessionKey = $sessKey;
    }

    public static function setAuthToken($tokenKey) {
        self::$authToken = $tokenKey;
    }
}
