<?php

class Company
{
    private static $conn;

    public static function getConnection()
    {
        if (empty(self::$conn)) {
            $connection = parse_ini_file('config/books.ini');
            self::$conn = new PDO(
                "mysql:host={$connection['host']};port={$connection['port']};dbname={$connection['name']}",
                "{$connection['user']}",
                "{$connection['pass']}",
                [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8']
            );
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$conn;
    }

    public static function save($company)
    {
        $conn = self::getConnection();
        if (empty($company['id'])) {
            $result = $conn->query("SELECT max(id) as next FROM companies");
            $row = $result->fetch();
            $company['id'] = (int)$row['next'] + 1;

            /* INSERT INTO companies (id, cnpj, name, fantasy, cep, address, neighborhood,
            complement, number, phone, mail, city, state) VALUES(:id, :cnpj, :name, :fantasy, :cep, :address, :neighborhood, :complement, :number, :phone, :mail, :city, :state)" */
            $sql = "INSERT INTO companies (id, cnpj, name, fantasy, cep, address, district, complement, number, phone, mail, city, state)
             VALUES(:id, :cnpj, :name, :fantasy, :cep, :address, :district, :complement, :number, :phone, :mail, :city, :state)";
            /*  $sql = "INSERT INTO people
             (id, name, cep, address, district, phone, mail, city, state) VALUES
             (:id, :name, :cep, :address, :district, :phone, :mail, :city, :state)"; */
        } else {
            $sql = "UPDATE companies SET cnpj = :cnpj, name = :name, fantasy = :fantasy, cep = :cep, address = :address, district = :district, complement = :complement, number = :number, phone = :phone,  mail = :mail, city = :city, state = :state  WHERE id = :id ";
        }

        $result = $conn->prepare($sql);

        return $result->execute(
            [
                ':id' => $company['id'],
                ':cnpj' => $company['cnpj'],
                ':name' => $company['name'],
                ':fantasy' => $company['fantasy'],
                ':cep' => $company['cep'],
                ':address' => $company['address'],
                ':district' => $company['district'],
                ':complement' => $company['complement'],
                ':number' => $company['number'],
                ':phone' => $company['phone'],
                ':mail' => $company['mail'],
                ':city' => $company['city'],
                ':state' => $company['state']
            ]
        );
    }

    /**
     * Busca uma Pessoa pelo seu $id
     * @param $id
     *
     * @return mixed
     */
    public static function find($id)
    {
        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM companies WHERE id='{$id}'");

        return $result->fetch();
    }

    public static function delete($id)
    {
        $conn = self::getConnection();
        $result = $conn->query("DELETE FROM companies WHERE id='{$id}'");

        return $result;
    }

    public static function all()
    {
        $conn = self::getConnection();
        $result = $conn->query("SELECT * FROM companies");

        return $result;
    }
}
