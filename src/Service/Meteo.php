<?php


namespace App\Service;

/**
 * Class Meteo
 * @package App\Service
 */
class Meteo extends AbstractEntityManagerService
{
    const URL = 'https://meteoserver.nl/api/uurverwachting.php?lat=%s&long=%s&key=%s';

    /**
     * @return float|int
     * @throws \Exception
     */
    public function getExpectedRainInMm()
    {
        try {
            $meteo = $this->getEntity();
            $latitude = $meteo->getLatitude();
            $longitude = $meteo->getLongitude();
            $apiKey = $meteo->getApiKey();

        } catch (\Exception $exception) {
            $latitude = 51.4571848;
            $longitude = 5.7962254;
            $apiKey = 'aa0df394de';
        }

        $curl = curl_init(sprintf(self::URL, $latitude, $longitude, $apiKey));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($result);

        $rain = 0;

        $now = (new \DateTime())->format('d-m-Y');
        foreach ($json->data as $row) {
            if (!preg_match('/^' . $now . '/', $row->tijd_nl)) {
                continue;
            }

            $rain += floatval($row->neersl);
        }

        return $rain;
    }

    /**
     * @return string
     */
    protected function getEntityClass(): string
    {
        return \App\Entity\Meteo::class;
    }
}