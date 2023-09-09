вызвали апи гет запросом
public function actionTest () {
    // создали экземпляр классов

$fastDeliveryService = new FastDeliveryService('https://fastDelivery.com');
$slowDeliveryService = new SlowDeliveryService('https://slowDelivery.com');

// кулькулятор
$deliveryCalculator = new DeliveryCalculator([
'Fast Delivery' => $fastDeliveryService,
'Slow Delivery' => $slowDeliveryService
]);

// посчитали стоимость доставки 
$selectedService = 'Fast Delivery';
$sourceKladr = '1234567890';
$targetKladr = '0987654321';
$weight = 10.5;

$cost = $deliveryCalculator->calculateCost($selectedService, $sourceKladr, $targetKladr, $weight);

//var_dump($cost);
return $cost;
}
}

//создали интерфейс
interface DeliveryServiceInterface {
    public function calculateCost(string $sourceKladr, string $targetKladr, float $weight): array;
}


class FastDeliveryService implements DeliveryServiceInterface {
    private $base_url;

    public function __construct(string $base_url) {
        $this->base_url = $base_url;
    }

    public function calculateCost(string $sourceKladr, string $targetKladr, float $weight): array {
        // сделали api request для расчета стоимости
        $price = 100; // пример цены
        $period = 5; // пример периода
        $error = "No error"; // текст ошибки
        
        return [
            'price' => $price,
            'period' => $period,
            'error' => $error
        ];
    }
}

class SlowDeliveryService implements DeliveryServiceInterface {
    private $base_url;

    public function __construct(string $base_url) {
        $this->base_url = $base_url;
    }

    public function calculateCost(string $sourceKladr, string $targetKladr, float $weight): array {
        // сделали api request для расчета стоимости
        $coefficient = 1.5; // коэффициент
        $date = "2023-09-09"; // дата
        $error = "No error"; // текст ошибки
        
        return [
            'price' => 150 * $coefficient,
            'date' => $date,
            'error' => $error
        ];
    }
}

class DeliveryCalculator {
    private $deliveryServices;

    public function __construct(array $deliveryServices) {
        $this->deliveryServices = $deliveryServices;
    }

    public function calculateCost(string $selectedService, string $sourceKladr, string $targetKladr, float $weight): array {
        $result = [];
        
        foreach ($this->deliveryServices as $name => $service) {
            $price = 0;
            $date = '';
            $error = '';

            if ($selectedService === $name) {
                // calculate cost using selected service
                $response = $service->calculateCost($sourceKladr, $targetKladr, $weight);
                $price = $response['price'];
                $date = $response['date'] ?? '';
                $error = $response['error'];
            } else {
                // calculate cost using other services
                $response = $service->calculateCost($sourceKladr, $targetKladr, $weight);
                $price = $response['price'];
                $date = $response['period'] ?? $response['date'] ?? '';
                $error = $response['error'];
            }

            $result[$name] = [
                'price' => $price,
                'date' => $date,
                'error' => $error
            ];
        }

        return $result;
    }
}


/*получили ответ
{
    "Fast Delivery": {
        "price": 100,
        "date": "",
        "error": "No error"
    },
    "Slow Delivery": {
        "price": 225,
        "date": "2023-09-09",
        "error": "No error"
    }
}
*/
