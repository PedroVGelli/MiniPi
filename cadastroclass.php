
<?php require'config.php'?>

<?php 
class cadastrar{
    private $usuario=['nome']
    private $email=['email']
    private $CPF=['CPF']
    private $telefone['telefone']
    private $senha['senha']
    private $nascimento['nascimento']

     // Construtor da classe
     public function __construct($usuario, $email, $nascimento, $CPF, $senha,$telefone) {
        $this->usuario = $usuario;
        $this->email = $email;
        $this->nascimento = $nascimento;
        $this->CPF = $CPF;
        $this->telefone = $telefone
        $this->senha = $telefone
    }

    // Getters para acessar as propriedades do usuario
    public function getusuario() {
        return $this->usuario;
    }

    public function getemail() {
        return $this->email;
    }

    public function getnascimento() {
        return $this->nascimento;
    }

    public function gettelefone() {
        return $this->telefone;
    }
    public function getCPF(){
        return $this->CPF;
    }
}

?>