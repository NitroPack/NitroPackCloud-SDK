<?php
namespace NitroPack\SDK\Utils;

use NitroPack\SDK\NitroPack;

class CjHandler {
    private $nitro;

    public function __construct($nitro) {
        $this->nitro = $nitro;
    }

    public function handleQueryParams() {
        if (!$this->isIgnoredParam("cjevent")) {
            return;
        }

        $_GET_lower = array_change_key_case($_GET, CASE_LOWER);
        if (empty($_GET_lower['cjevent']) || empty($_SERVER['HTTP_HOST'])) {
            return;
        }
        $cookie_name = "cje";
        $domain = preg_replace("/^(www.)?(.*)$/", ".$2", $_SERVER['HTTP_HOST']);
        $cjevent = $_GET_lower["cjevent"];
        setcookie($cookie_name, $cjevent, time() + (86400 * 395), "/", $domain, true, false);
    }

    public function isIgnoredParam($paramName) {
        $config = $this->nitro->getConfig();
        $ignoredParams = $config->IgnoredParams;

        if ($ignoredParams) {
            foreach ($ignoredParams as $ignorePattern) {
                $regex = "/^" . NitroPack::wildcardToRegex($ignorePattern) . "$/";
                if (preg_match($regex, $paramName)) {
                    return true;
                }
            }
        }

        return false;
    }
}