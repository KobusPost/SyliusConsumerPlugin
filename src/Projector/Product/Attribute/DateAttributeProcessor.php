<?php

declare(strict_types=1);

namespace Sylake\SyliusConsumerPlugin\Projector\Product\Attribute;

use Sylake\SyliusConsumerPlugin\Model\Attribute;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;

final class DateAttributeProcessor implements AttributeProcessorInterface
{
    /** @var AttributeValueProviderInterface */
    private $attributeValueProvider;

    public function __construct(AttributeValueProviderInterface $attributeValueProvider)
    {
        $this->attributeValueProvider = $attributeValueProvider;
    }

    /** {@inheritdoc} */
    public function process(ProductInterface $product, Attribute $attribute): array
    {
        if (!$this->supports($attribute)) {
            return [];
        }

        /** @var ProductAttributeValueInterface|null $attributeValue */
        $attributeValue = $this->attributeValueProvider->provide($product, $attribute->attribute(), $attribute->locale());
        if (null === $attributeValue) {
            return [];
        }

        // Strip everything except the year, month and day
        $dateTime = \DateTime::createFromFormat(\DateTime::ATOM, $attribute->data());

        if (!$dateTime instanceof \DateTime) {
            return [];
        }

        $format = $dateTime->format('Y-m-d');
        $attributeValue->setValue(\DateTime::createFromFormat(
            '!Y-m-d',
            $format
        ));

        return [$attributeValue];
    }

    private function supports(Attribute $attribute): bool
    {
        return is_string($attribute->data())
            && '' !== $attribute->data()
            && false !== \DateTime::createFromFormat(\DateTime::ATOM, $attribute->data())
        ;
    }
}
