import axios from 'axios';

export const validateHashPlate = async (payload) =>  {

    try{

        const response = await axios.post('/hash-plate', payload);
        return response;

    }catch(error){
        throw error;
    }

}

export const uploadMoovie = async (payload) => {

    try{

        const response = await axios.post('/hash-plate/upload-moovie', payload, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return  response;

    }catch(error){
        throw error;
    }

}