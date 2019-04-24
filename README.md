Сериализация объектов содержащих замыкания (Closures)

Используется библиотека `jeremeamia/SuperClosure`

```php
$serializer = new \harlam\Serialize\Serializer(
    new SuperClosure\Serializer()
);

$objectWithClosures = new stdClass();
$objectWithClosures->property = function () {
    return 'something';
};

// Сериализация и десериализация
$serialized = $serializer->serialize($objectWithClosures);
$restored = $serializer->unserialize($serialized);

// Трансформация / восстановление замыканий
$serializer->transformClosures($objectWithClosures);
var_dump($objectWithClosures);

$serializer->restoreClosures($objectWithClosures);
var_dump($objectWithClosures);
```