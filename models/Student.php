<?php

require_once __DIR__ . '/../config/Database.php';

class Student
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /** Return all students ordered by last name. */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM students ORDER BY last_name, first_name'
        );
        return $stmt->fetchAll();
    }

    /** Return one student by primary key, or false if not found. */
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM students WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Insert a new student record.
     * Returns the new row's id on success.
     * Throws PDOException on duplicate student_no / email.
     */
    public function create(array $data): int
    {
        $sql = '
            INSERT INTO students (student_no, first_name, last_name, email, course, year_of_study)
            VALUES (:student_no, :first_name, :last_name, :email, :course, :year_of_study)
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':student_no'    => $data['student_no'],
            ':first_name'    => $data['first_name'],
            ':last_name'     => $data['last_name'],
            ':email'         => $data['email'],
            ':course'        => $data['course'],
            ':year_of_study' => $data['year_of_study'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Update an existing student record.
     * Returns true when exactly one row was affected.
     */
    public function update(int $id, array $data): bool
    {
        $sql = '
            UPDATE students
            SET student_no    = :student_no,
                first_name    = :first_name,
                last_name     = :last_name,
                email         = :email,
                course        = :course,
                year_of_study = :year_of_study
            WHERE id = :id
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':student_no'    => $data['student_no'],
            ':first_name'    => $data['first_name'],
            ':last_name'     => $data['last_name'],
            ':email'         => $data['email'],
            ':course'        => $data['course'],
            ':year_of_study' => $data['year_of_study'],
            ':id'            => $id,
        ]);
        return $stmt->rowCount() === 1;
    }

    /** Delete a student by id. Returns true when a row was removed. */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM students WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() === 1;
    }

    /** Return true when the given student_no already exists (optionally excluding $excludeId). */
    public function studentNoExists(string $studentNo, int $excludeId = 0): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1 FROM students WHERE student_no = ? AND id != ? LIMIT 1'
        );
        $stmt->execute([$studentNo, $excludeId]);
        return (bool) $stmt->fetch();
    }

    /** Return true when the given email already exists (optionally excluding $excludeId). */
    public function emailExists(string $email, int $excludeId = 0): bool
    {
        $stmt = $this->db->prepare(
            'SELECT 1 FROM students WHERE email = ? AND id != ? LIMIT 1'
        );
        $stmt->execute([$email, $excludeId]);
        return (bool) $stmt->fetch();
    }
}
