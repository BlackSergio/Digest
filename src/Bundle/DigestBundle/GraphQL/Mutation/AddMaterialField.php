<?php

namespace DS\Digest\Bundle\DigestBundle\GraphQL\Mutation;

use DS\Digest\Bundle\DigestBundle\GraphQL\Mutation\Input\SingleMaterial;
use DS\Digest\Bundle\DigestBundle\GraphQL\Type\MaterialType;
use DS\Digest\Domain\Digest\Model\Material;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQLBundle\Field\AbstractContainerAwareField;

/**
 * Class AddMaterialField
 * @package DS\Digest\Bundle\DigestBundle\GraphQL\Mutation
 */
class AddMaterialField extends AbstractContainerAwareField
{
    /** {@inheritdoc} */
    public function getType()
    {
        return new MaterialType();
    }

    /** {@inheritdoc} */
    public function build(FieldConfig $config)
    {
        $config->addArguments([
            'url' => new NonNullType(new StringType()),
            'title' => new StringType(),
            'description' => new StringType(),
        ]);

        parent::build($config);
    }

    /** {@inheritdoc} */
    public function resolve($value, array $args, ResolveInfo $info)
    {
        $material = new Material(
            $args['url'],
            $args['title'] ?? '',
            $args['description'] ?? ''
        );

        $documentManager = $this->container->get('doctrine_mongodb.odm.document_manager');
        $documentManager->persist($material);
        $documentManager->flush($material);

        return $material;
    }
}
