const axios = require("axios");

module.exports = {
    get: async (url) => {
        url = "https://jsonplaceholder.typicode.com/posts";
        const response = await axios.get(url);
        response.data.data = response.data
        return response;
    },
};
