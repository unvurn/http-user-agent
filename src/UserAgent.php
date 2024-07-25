<?php

declare(strict_types=1);

namespace Unvurn\Http;

use Unvurn\Http\UserAgent\Product;

class UserAgent
{
    /** @var Product[] $products */
    private array $products = [];

    public function __construct(private readonly string $value) {
        $this->products = $this->parse($this->value);
    }

    public function product(string $name): ?Product
    {
        if (!array_key_exists($name, $this->products)) {
            return null;
        }
        return $this->products[$name];
    }

    public function productVersion(string $name): ?string
    {
        if (!array_key_exists($name, $this->products)) {
            return null;
        }
        return $this->products[$name]->version;
    }

    /**
     * @param string $str
     * @return Product[]
     */
    private function parse(string $str): array
    {
        $products = [];

        $j0 = 0;
        $i0 = null;
        $lv = 0;
        $productName = null;
        $productVersion = null;

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
                        $comment = substr($str, $j0, $i - $j0);
                        $products[$productName] = new Product($productVersion, $comment);

                        $productName = null;
                        $productVersion = null;
                        $j0 = $i + 1;
                    }
                    $lv--;
                    break;
                case ' ':
                    if ($lv == 0 && !is_null($i0)) {
                        [$productName, $productVersion] = $appender($str, $i0, $i);
                        $i0 = null;
                    }
                    break;
                default:
                    if ($lv == 0) {
                        if (is_null($i0)) {
                            $i0 = $i;
                        } else if (!is_null($productName)) {
                            $products[$productName] = new Product($productVersion, null);

                            $productName = null;
                            $productVersion = null;
                        }
                    }
                    break;
            }
        }

        if (!is_null($i0)) {
            $pv = $appender($str, $i0, $i);
            $product = new Product($pv[1], null);
            $products[$pv[0]] = $product; // $pv[1];

            $productName = null;
            $productVersion = null;
        }

        return $products;
    }
}
