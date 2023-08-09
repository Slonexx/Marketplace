<?php

namespace App\Services\AdditionalServices;

use App\Clients\MsClient;
use GuzzleHttp\Exception\ClientException;

class AttributeService
{
    public function setAllAttributesMs($data): void
    {
        $apiKeyMs = $data['tokenMs'];
        //$accountId = $data['accountId'];

        try {
            $docAttributes = $this->getDocAttributes();
            $payDocAttributes = $this->getPayDocAttributes();

            $this->createAttributes($apiKeyMs, 'customerorder', $docAttributes);
            $this->createAttributes($apiKeyMs, 'demand', $docAttributes);
            $this->createAttributes($apiKeyMs, 'salesreturn', $docAttributes);
            $this->createAttributesCustomentity($apiKeyMs);

            $this->createAttributes($apiKeyMs, 'paymentin', $payDocAttributes);
            $this->createAttributes($apiKeyMs, 'paymentout', $payDocAttributes);
            $this->createAttributes($apiKeyMs, 'cashin', $payDocAttributes);
            $this->createAttributes($apiKeyMs, 'cashout', $payDocAttributes);
        } catch (ClientException) {
        }
    }

    private function createAttributes($apiKeyMs, $entityType, $attributes): void
    {
        $url = "https://online.moysklad.ru/api/remap/1.2/entity/" . $entityType . "/metadata/attributes";
        $client = new MsClient($apiKeyMs);
        $json = $client->get($url);

        foreach ($attributes as $attribute) {
            if (!$this->isAttributeExists($json, $attribute['name'])) {
                $client->post($url, $attribute);
            }
        }
    }

    private function isAttributeExists($json, $attributeName): bool
    {
        foreach ($json->rows as $row) {
            if ($attributeName == $row->name) {
                return true;
            }
        }
        return false;
    }

    public function getDocAttributes(): array
    {
        return [
            0 => [
                "name" => "id-билета (ReKassa)",
                "type" => "string",
                "required" => false,
                "show" => false,
                "description" => "Данное дополнительное поле отвечает за синхронизацию с ReKassa",
            ],
            1 => [
                "name" => "Фискализация (ReKassa)",
                "type" => "boolean",
                "required" => false,
                "description" => "Данное дополнительное поле отвечает за проведения фискализации, если стоит галочка то фискализация была (ReKassa)",
            ],
        ];
    }

    public function getPayDocAttributes(): array
    {
        return [
            0 => [
                "name" => "Фискализация (ReKassa)",
                "type" => "boolean",
                "required" => false,
                "description" => "Данное дополнительное поле отвечает за проведения фискализации, если стоит галочка то фискализация была (ReKassa)",
            ],
        ];
    }

    private function createAttributesCustomentity(mixed $apiKeyMs): void
    {
        $client = new MsClient($apiKeyMs);
        $json = $client->post("https://online.moysklad.ru/api/remap/1.2/entity/customentity/", ['name' => 'Тип оплаты (Онлайн ККМ)']);
        $client->post("https://online.moysklad.ru/api/remap/1.2/entity/customentity/" . $json->id, [
            ['name' => "Наличные"],
            ['name' => "Картой"],
            ['name' => "Мобильная"],
        ]);



        $client->post("https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes",
            [
                "customEntityMeta" => [
                    "href" => 'https://online.moysklad.ru/api/remap/1.2/context/companysettings/metadata/customEntities/'. $json->id,
                    "type" => "customentitymetadata",
                    "mediaType" => "application/json",
                ],
                "name" => "Тип оплаты (Онлайн ККМ)",
                "type" => "customentity",
                "required" => false,
            ]
        );

    }

}
