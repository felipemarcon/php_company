<?php

class Person
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

    public static function save($person)
    {
        $conn = self::getConnection();
        if (empty($person['id'])) {
            $result = $conn->query("SELECT max(id) as next FROM companies");
            $row = $result->fetch();
            $person['id'] = (int)$row['next'] + 1;

            /* INSERT INTO companies (id, cnpj, name, fantasy, cep, address, neighborhood, 
            complement, number, phone, mail, city, uf) VALUES(:id, :cnpj, :name, :fantasy, :cep, :address, :neighborhood, :complement, :number, :phone, :mail, :city, :uf)" */
            $sql = "INSERT INTO companies (id, cnpj, name, fantasy, cep, address, neighborhood, complement, number, phone, mail, city, uf)
             VALUES(:id, :cnpj, :name, :fantasy, :cep, :address, :neighborhood, :complement, :number, :phone, :mail, :city, :uf)";
            /*  $sql = "INSERT INTO people
             (id, name, cep, address, district, phone, mail, city, state) VALUES
             (:id, :name, :cep, :address, :district, :phone, :mail, :city, :state)"; */
        } else {
            $sql = "UPDATE companies SET cnpj = :cnpj, name = :name, fantasy = :fantasy, cep = :cep, address = :address, neighborhood = :neighborhood, complement = :complement, number = :number, phone = :phone,  mail = :mail, city = :city, uf = :uf  WHERE id = :id ";
        }

        $result = $conn->prepare($sql);

        return $result->execute(
            [
                ':id' => $person['id'],
                ':cnpj' => $person['cnpj'],
                ':name' => $person['name'],
                ':fantasy' => $person['fantasy'],
                ':cep' => $person['cep'],
                ':address' => $person['address'],
                ':neighborhood' => $person['district'],
                ':complement' => $person['complement'],
                ':number' => $person['number'],
                ':phone' => $person['phone'],
                ':mail' => $person['mail'],
                ':city' => $person['city'],
                ':uf' => $person['state']
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
