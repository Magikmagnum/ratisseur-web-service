<?php

namespace App\Exception;

class DTOError extends \Exception
{
    protected string $class = '';
    protected string $method = '';
    protected int $arg_num = 0;
    protected string $arg_name = '';
    protected string $expected_type = '';
    protected string $given_type = '';
    protected string $file = '';
    protected int $line = 0;

    protected string $messageError;
    protected array $details = [];

    const __PATTERN = '/^([\w\\\\]+)::(\w+)\(\): Argument #(\d+) \((\$\w+)\) must be of type (.+?), ([\s\S]+?) given, called in ([\s\S]+?) on line (\d+)$/';

    public function __construct(\Throwable $th)
    {
        parent::__construct($th->getMessage(), $th->getCode(), $th);

        // Recherche des correspondances
        if (preg_match(self::__PATTERN, $th->getMessage(), $matches)) {
            // Initialisation des attributs avec les valeurs des correspondances
            $this->class = $matches[1];
            $this->method = $matches[2];
            $this->arg_num = (int)$matches[3];
            $this->arg_name = $matches[4];
            $this->expected_type = $matches[5];
            $this->given_type = $matches[6];
            $this->file = $matches[7];
            $this->line = (int)$matches[8];

            // Construction du message d'erreur
            $this->messageError = sprintf(
                'Le champ "%s" doit être de type %s, mais vous avez fourni un type %s.',
                $this->arg_name,
                $this->expected_type,
                $this->given_type
            );
        } else {
            // Message d'erreur par défaut si aucune correspondance n'est trouvée
            $this->messageError = 'Impossible d\'extraire le message d\'erreur.';
        }

        // Retournez le message d'erreur et le nom du champ
        $errorDetails =  [
            'field' => isset($this->arg_name) ? substr($this->arg_name, 1) : null, // Vérifie si le nom du champ est défini dans les correspondances
            'message' => $this->messageError
        ];

        // Définir les détails de l'erreur
        $this->details[] = $errorDetails;
    }

    // Getters et Setters pour chaque attribut
    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getArgNum(): int
    {
        return $this->arg_num;
    }

    public function setArgNum(int $arg_num): void
    {
        $this->arg_num = $arg_num;
    }

    public function getArgName(): string
    {
        return $this->arg_name;
    }

    public function setArgName(string $arg_name): void
    {
        $this->arg_name = $arg_name;
    }

    public function getExpectedType(): string
    {
        return $this->expected_type;
    }

    public function setExpectedType(string $expected_type): void
    {
        $this->expected_type = $expected_type;
    }

    public function getGivenType(): string
    {
        return $this->given_type;
    }

    public function setGivenType(string $given_type): void
    {
        $this->given_type = $given_type;
    }

    public function getMessageError(): string
    {
        return $this->messageError;
    }

    public function setMessageError(string $messageError): void
    {
        $this->messageError = $messageError;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $details): void
    {
        $this->details = $details;
    }
}
