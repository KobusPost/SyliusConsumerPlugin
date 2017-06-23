<?php

declare(strict_types=1);

namespace Sylake\SyliusConsumerPlugin\Attribute;

use Sylake\SyliusConsumerPlugin\Model\Attribute;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class StringAttributeProcessor implements AttributeProcessorInterface
{
    /** @var AttributeValueProviderInterface */
    private $attributeValueProvider;

    /** @var AttributeOptionResolverInterface */
    private $attributeOptionResolver;

    public function __construct(
        AttributeValueProviderInterface $attributeValueProvider,
        AttributeOptionResolverInterface $attributeOptionResolver
    ) {
        $this->attributeValueProvider = $attributeValueProvider;
        $this->attributeOptionResolver = $attributeOptionResolver;
    }

    /** {@inheritdoc} */
    public function process(ProductInterface $product, Attribute $attribute): void
    {
        if (!$this->supports($attribute)) {
            return;
        }

        /** @var AttributeValueInterface|null $attributeValue */
        $attributeValue = $this->attributeValueProvider->provide($product, $attribute->attribute(), $attribute->locale());
        if (null === $attributeValue) {
            return;
        }

        $attributeValue->setValue(null === $attribute->data() ? '' : $this->attributeOptionResolver->resolve(
            $attribute->attribute(),
            $attribute->locale(),
            $attribute->data()
        ));

        $product->addAttribute($attributeValue);
    }

    private function supports(Attribute $attribute): bool
    {
        return is_string($attribute->data()) || null === $attribute->data();
    }
}
