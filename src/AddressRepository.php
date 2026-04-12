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

        $sql = <<<'SQL'
            SELECT id, full_address
            FROM fias_addresses
            WHERE full_address ILIKE :query
            ORDER BY full_address
            LIMIT :limit
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':query', '%' . $normalizedQuery . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', max(1, min(20, $limit)), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
