<?php


namespace App\Service;

/**
 * Class BuienRadar
 * @package App\Service
 */
class BuienRadar extends AbstractEntityManagerService
{
    const URL = 'https://data.buienradar.nl/2.0/feed/json';

    /**
     * @return float
     * @throws \Exception
     */
    public function getRainInPast24Hours()
    {
        try {
            $location = $this->getEntity()->getLocation();
        } catch (\Exception $exception) {
            $location = 6370;
        }

        $curl = curl_init(self::URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($result);
        if (!$json) {
            throw new \Exception('Not possible to read data from buienradar');
        }

        foreach ($json->actual->stationmeasurements as $stationmeasurement) {
            if ($stationmeasurement->{'stationid'} !== $location) {
                continue;
            }

            if (!isset($stationmeasurement->rainFallLast24Hour)) {
                continue;
            }

            return floatval($stationmeasurement->rainFallLast24Hour);
        }

        throw new \Exception('Unable to find rainfal for station ' . $location);
    }

    /**
     * @return string
     */
    protected function getEntityClass(): string
    {
        return \App\Entity\BuienRadar::class;
    }
}