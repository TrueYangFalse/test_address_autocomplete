<?php

declare(strict_types=1);

final class AddressRepository
{
    public function __construct(private PDO $pdo) {}

    public function searchByQuery(string $query, int $limit = 10): array
    {
        $normalizedQuery = trim($query);

        if ($normalizedQuery === '') {
            return [];
        }

        $tokens = preg_split('/[\s,.;:()\-]+/u', mb_strtolower($normalizedQuery), -1, PREG_SPLIT_NO_EMPTY);        if (!$tokens) {
            return [];
        }

        $conditions = [];
        $params = [];
        foreach ($tokens as $index => $token) {
            $paramName = ':token' . $index;
            $conditions[] = "full_address ILIKE {$paramName}";
            $params[$paramName] = '%' . $token . '%';
        }

        $sql = '
            SELECT id, full_address
            FROM fias_addresses
            WHERE ' . implode(' AND ', $conditions) . '
            ORDER BY full_address
            LIMIT :limit
        ';

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $paramName => $value) {
            $stmt->bindValue($paramName, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', max(1, min(20, $limit)), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
