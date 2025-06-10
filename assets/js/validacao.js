function comparaSenhas(){

}

function mascaraCep(input) {     
    let cep = input.value.replace(/\D/g, '') // Remove tudo que não é número
    if (cep.length > 5) { // Verifica se o comprimento do CEP é maior que 5
        cep = cep.replace(/^(\d{5})(\d)/, '$1-$2') // Adiciona o traço no lugar certo
    }     
    input.value = cep; 
}

function mascaraCPF(input){
    let cpf=input.value.replace(/\D/g,'')
    if(cpf.length > 3){
        cpf = cpf.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})/,'$1.$2.$3-$4')
    }
    input.value = cpf
}


function mascaraTelefone(campo) {
    let valor = campo.value.replace(/\D/g, '');
  
    if (valor.length > 11) valor = valor.slice(0, 11);
  
    if (valor.length >= 2 && valor.length <= 6)
      campo.value = `(${valor.slice(0, 2)}) ${valor.slice(2)}`;
    else if (valor.length > 6)
      campo.value = `(${valor.slice(0, 2)}) ${valor.slice(2, 7)}-${valor.slice(7)}`;
    else
      campo.value = valor;
  }


  function togglePassword() {
    const senhaInput = document.getElementById("senha");
    const senhaType = senhaInput.getAttribute("type");
    senhaInput.setAttribute("type", senhaType === "password" ? "text" : "password");

    //if ternário - sintaxe
    //não tem a palavra if
    //teste lógico ? ação se V : ação se F
    //type password: *******
    //type text: sersdfsf

}


function atualizarUsuario() {
  fetch('usuario_logado.php')
    .then(response => response.json())
    .then(data => {
      const caixa = document.getElementById('usuario-logado-box');
      if (data.usuario) {
        caixa.textContent = 'Usuário: ' + data.usuario;
      } else {
        caixa.textContent = 'Usuário não logado';
      }
    })
    .catch(err => {
      console.error('Erro ao buscar usuário:', err);
    });
}

// Atualiza na inicialização
atualizarUsuario();

// Atualiza a cada 5 segundos
setInterval(atualizarUsuario, 5000);