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



document.addEventListener('DOMContentLoaded', () => {
  const isbnInput = document.getElementById('isbn');

  isbnInput.addEventListener('input', function(e) {
    let value = isbnInput.value;

    // Remove tudo que não for número ou hífen
    value = value.replace(/[^0-9\-]/g, '');

    // Remove hífens existentes para reformatar
    value = value.replace(/\-/g, '');

    // Aplica a máscara ISBN-13: 978-3-16-148410-0
    // Coloca hífen após os dígitos 3, 4, 6 e 12
    let formatted = '';
    for (let i = 0; i < value.length && i < 13; i++) {
      formatted += value[i];
      if (i === 2 || i === 3 || i === 5 || i === 11) {
        formatted += '-';
      }
    }

    isbnInput.value = formatted;
  });
});


document.addEventListener("DOMContentLoaded", function () {
  const botoes = document.querySelectorAll(".mostrar-detalhes");

  botoes.forEach((botao) => {
    botao.addEventListener("click", () => {
      const detalhes = botao.nextElementSibling;
      const estaVisivel = detalhes.style.display === "block";

      detalhes.style.display = estaVisivel ? "none" : "block";
      botao.textContent = estaVisivel ? "Exibir Detalhes" : "Ocultar Detalhes";
    });
  });
});


