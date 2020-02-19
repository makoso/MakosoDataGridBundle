<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 29.11.17
 * Time: 13:09
 */

namespace Makoso\DatagridBundle\Grid\Filter;


use Doctrine\Common\Collections\ArrayCollection;

interface FilterGroupInterface
{
    public function getFilters():ArrayCollection;

    public function getFirstInputType(): string;

    public function setFirstInputType(string $firstInputType): void;

    public function getSecondInputType(): string;

    public function setSecondInputType(string $secondInputType): void;

    public function getFirstInputOptions(): array;

    public function setFirstInputOptions(array $firstInputOptions): void;

    public function getSecondInputOptions(): array;

    public function setSecondInputOptions(array $secondInputOptions): void;
}