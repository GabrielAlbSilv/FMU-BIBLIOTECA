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