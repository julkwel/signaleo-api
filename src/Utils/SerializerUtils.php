<?php
/**
 * Julien Rajerison <julienrajerison5@gmail.com>
 **/

namespace App\Utils;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class SerializerUtils.
 */
class SerializerUtils
{
    /**
     * @param object|array $data
     *
     * @return string
     */
    public function serialize($data)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer(),new DateTimeNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($data, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }
}
