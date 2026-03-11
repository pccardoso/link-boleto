import axios from 'axios';

export const getAllVehicle = async (cpfOrCnpj) => {

    try{

        const response = await axios.get(`/search-plate/${cpfOrCnpj}`);
        return response;

    }catch(error){
        throw error;
    }

}

export const getAllBoletOfPeople = async (cpfOrCnpj) => {

    try{

        const response = await axios.post(`/search-boleto-cpf/${cpfOrCnpj}`);
        return response;

    }catch(error){
        throw error;
    }

}

export const getAllBoletOfPlate = async (plate) => {

    try{

        const response = await axios.post(`/search-boleto-plate/${plate}`);
        return response;

    }catch(error){
        throw error;
    }

}

export const updateBoleto = async (codigo_boleto) => {

    try{

        const response = await axios.post(`alterar/vencimento-boleto/${codigo_boleto}`);
        return response;

    }catch(error){
        throw error;
    }
    
}