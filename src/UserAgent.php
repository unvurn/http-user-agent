<?php

declare(strict_types=1);

namespace Unvurn\Http;

class UserAgent
{
    private array $productVersions = [];

    public function __construct(private readonly string $value, bool $passThroughOnComments = true) {
        $this->productVersions = $this->parse($this->value, $passThroughOnComments);
    }

    public function productVersion(string $productName): ?string {
        if (!array_key_exists($productName, $this->productVersions)) {
            return null;
        }
        return $this->productVersions[$productName];
    }

    private function parse(string $str, bool $passThroughOnComments): array
    {
        $productVersionList = [];

        $j0 = 0;
        $i0 = null;
        $lv = 0;
        $product = null;

        $appender = function($str, $i0, $i) {
            $pv = explode('/', substr($str, $i0, $i - $i0 - ($str[$i - 1] == ',' || $str[$i - 1] == ';' || $str[$i - 1] == ')' ? 1 : 0)), 2);
            return [$pv[0], count($pv) > 1 ? $pv[1] : null];
        };

        $len = strlen($str);
        for ($i = 0; $lv >= 0 && $i < $len; $i++) {
            $c = $str[$i];
            switch ($c) {
                case '(':
                    if ($lv == 0) {
                        $j0 = $i + 1;
                    }
                    $lv++;
                    break;
                case ')':
                    if ($j0 < $i && $lv == 1) {
                        if ($passThroughOnComments) {
                            $productVersionList[$product . ":comment"] = substr($str, $j0, $i - $j0);
                        } else {
                            $productVersionList[$product . ":comment"] = $this->parse(substr($str, $j0, $i - $j0), false);
                        }
                        $j0 = $i + 1;
                    }
                    $lv--;
                    break;
                case ' ':
                    if ($lv == 0 && !is_null($i0)) {
                        [$product, $version] = $appender($str, $i0, $i);
                        $productVersionList[$product] = $version;
                        $i0 = null;
                    }
                    break;
                default:
                    if ($lv == 0 && is_null($i0)) {
                        $i0 = $i;
                    }
                    break;
            }
        }

        if (!is_null($i0)) {
            $pv = $appender($str, $i0, $i);
            $productVersionList[$pv[0]] = $pv[1];
        }

        return $productVersionList;
    }
}
