<?php

declare(strict_types=1);

namespace OpenClassrooms\OpenAPIValidation\Schema;

use function array_unshift;

// Breadcrumb addresses a value in a complex structure.
// It can address an index in the compound array(object)
class BreadCrumb
{
    /** @var self|null link to a previous crumb */
    protected $prevCrumb;

    /** @param int|string|null $compoundIndex suitable for array index */
    public function __construct(protected int|string|null $compoundIndex = null)
    {
    }

    /** @return BreadCrumb */
    public function addCrumb(string|int $index): self
    {
        $i            = new self($index);
        $i->prevCrumb = $this;

        return $i;
    }

    /**
     * Follow the chain of crumbs to build a full chain of keys
     *
     * @return mixed[] - string/int values are allowed
     */
    public function buildChain(): array
    {
        $keys = [];

        $crumb = $this;
        do {
            array_unshift($keys, $crumb->compoundIndex);
            $crumb = $crumb->prevCrumb;
        } while ($crumb && ($crumb->compoundIndex !== null));

        return $keys;
    }
}
