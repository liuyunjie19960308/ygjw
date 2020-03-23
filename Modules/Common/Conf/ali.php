<?php
/**
 * 阿里相关配置
 */
return [
    // 
    'ALIPAY_CONFIG_WAP' => [
        //应用ID,您的APPID。
        'app_id'                => "2019083066697697",
        //商户私钥
        'merchant_private_key'  => "MIIEowIBAAKCAQEArcsVwJaVRgDGHXByjCyLHyJwxQPqkVQYL5eI+Bh1FxZIT6QUjwPQdbzFGMVl5/x5S6+epVJPLMopDx4N486kimOGn+VGoKkist5Qed0BP7D3JCoZwjjR5B5tBM1cRBz1OYmGt6eVra9f9Nr9W2CZ2UnhOLaKZYhPxgW9I+SuMVeeZNgI48iS7IehpAzUQKmlpZ0Ecgq2Q7WnuvOTc7rDzMnvKydYBuHssVKKtKOMCsuU2dWs4zCvdckDzqTrVaADNpFmgyIemds16vOcHYi6BJJHgYOO8zO2rH1hZ4A1LJ1sNfXnK254zqduwW4V8u5VRBFfopn9gh/M5oXZzn1GWQIDAQABAoIBACAJbNQYriG+aMbqgKMoXuj5X1JiG2540xCK3ZvmXkdIO0I7G/MVH+tkRe0ADn4KQ43ZLOSK3L+D8LexsZvJ7vj3ycxV5oQdSaExbCJaxC0qgRRwklkEQmTL2wrklFqV//qASI44XVYsGwM3Dzc2AoZUoSjkNXTsANCfJDOmpxm/IB+NULtW6aJpOBqcw8rNX6w5t7nrVK0Kkmblz1bwPJMINYte4NvwYmWBj/Afka1q8R9r2/v+VsPrvgUunm5ZjUkkSWRaMF2k3nIm/BFhMH2tyk5tC1V2eHr3rSAD5kU9aJDyxMIfyV7enEr5OcKkbpDe3YslIFXkB+y81kosqgECgYEA4zZ6s38EnNXBHHfwEUYtZnXwySj/WWhy7h8JamfT0Kmdzet4BGZEL/X1PSfWj6UjxbF35Z0Zo/LlFnU/CYByoT6pKlRy1fmDPu2gb9vGPq01VgzYP1sDtkkyaBexReLPtexRtpmRZSrIU7IBnkBUCXNMTT/puTlloehWhwOGucECgYEAw8/5RFe9sIOs7r72wkRkwgYL+GqbzeuHkBvFwBilgNTLRyrIQ0W5zqlE0ZfqNimBGrGCpBRvLjcqqV9RVfTLJvW7zxTHIPYuN89q5r62o6B2sJEDrfcfysnO5xg5xSi2C+5o+BQms9iX5yGzL2ucdAHQ82eySnM6KUgJwJ80wpkCgYEAtZWcikdA0HakVrQj0Cpdrj0jqiBxsmqfL17uj3Na/LARxbghuqJgbFQNIkrsVvCLnjsurvrWuwgrvb8GGfnloqgJWiMTg55dHbWbOspRrVWQAq1RRZDfbpchCb+llUym46VxyJUCde+zGfBxCqAuiT70A+jISZdtee/M9yoCSgECgYAUZpql0C5nMZDW3vZ+jvmgbVjZ7OGGtr9M+FfGB8tWfNUg+QgcWitdqK0O2TEBq5lT2qKtzaM3wW+kbdXfir2PtZJ3pMaKXJu8HNQabkxBB/rVsoYbnd+mFRiFYzTBodg1rgUps4/EtRA+eHhevGt0eqv2cBtXPmIU4viBJRUuIQKBgHE6iHpzZ0oWOLhHT4qwRKh9z4lQZI8AfPxhJhonShruSE+C5AQRAy4eF+GlHurK+pJ7+4k3oNvoWY1VE7iRbTJOjfPTXNF31rxbIHPX5ddPsTh0xWELc/XNs1gmxhrnSV41l+FZqzZQIobh3j/HcTl7if6BU8VqrtzStnBXN9DA",
        //异步通知地址
        'notify_url'            => "http://pay.logo.toocms.com/Pay/aliCallback",
        //同步跳转
        'return_url'            => "http://logo.toocms.com/index.php/System/aliPayCallback",
        //编码格式
        'charset'               => "UTF-8",
        //签名方式
        'sign_type'             =>"RSA2",
        //支付宝网关
        'gatewayUrl'            => "https://openapi.alipay.com/gateway.do",
        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key'     => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArcsVwJaVRgDGHXByjCyLHyJwxQPqkVQYL5eI+Bh1FxZIT6QUjwPQdbzFGMVl5/x5S6+epVJPLMopDx4N486kimOGn+VGoKkist5Qed0BP7D3JCoZwjjR5B5tBM1cRBz1OYmGt6eVra9f9Nr9W2CZ2UnhOLaKZYhPxgW9I+SuMVeeZNgI48iS7IehpAzUQKmlpZ0Ecgq2Q7WnuvOTc7rDzMnvKydYBuHssVKKtKOMCsuU2dWs4zCvdckDzqTrVaADNpFmgyIemds16vOcHYi6BJJHgYOO8zO2rH1hZ4A1LJ1sNfXnK254zqduwW4V8u5VRBFfopn9gh/M5oXZzn1GWQIDAQAB",
    ],
];
