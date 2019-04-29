<?php
namespace App\DomainModels;


use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
class ObjectToArray
{

    private $normalizer;

    public function __construct()
    {
        //set decorator
        $object[] = new ObjectNormalizer();
        $serializer = new Serializer($object);
 
        $this->normalizer = $serializer;
    }

    public function setObject($topic)
    {
        $data = $this->normalizer->normalize($topic, null, array($topic));
        return $data;
    }

    public function denormalize($data){

        return $this->normalizer->denormalize($data,'myObj',null, array($data));
    }
    
            // $encoders = [new XmlEncoder(), new JsonEncoder()];
            // $normalizers = [new ObjectNormalizer()];
            
            // $serializer = new Serializer($normalizers, $encoders);

            // $data = $serializer->normalize($products, null, $products);
}