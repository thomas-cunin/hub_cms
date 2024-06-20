<?php

namespace App\Utils;

use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class FormConstraintHelper
{
    private MetadataFactoryInterface $metadataFactory;

    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function getFieldAttributes(string $entityClass, string $propertyName): array
    {
        /** @var ClassMetadata $metadata */
        $metadata = $this->metadataFactory->getMetadataFor($entityClass);
        $propertyMetadata = $metadata->getPropertyMetadata($propertyName);

        if (empty($propertyMetadata)) {
            return [];
        }

        $constraints = $propertyMetadata[0]->getConstraints();
        $attributes = [];

        foreach ($constraints as $constraint) {
            if ($constraint instanceof Length) {
                if ($constraint->max !== null) {
                    $attributes['maxlength'] = $constraint->max;
                }
                if ($constraint->min !== null) {
                    $attributes['minlength'] = $constraint->min;
                }
                if ($constraint->maxMessage !== null) {
                    $attributes['data-parsley-maxlength-message'] = $constraint->maxMessage;
                }
                if ($constraint->minMessage !== null) {
                    $attributes['data-parsley-minlength-message'] = $constraint->minMessage;
                }
            }
            if ($constraint instanceof NotBlank) {
                $attributes['required'] = true;
            }
        }

        return $attributes;
    }
}
