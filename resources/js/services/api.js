export const api = {
    async loadItems({ page = 1, itemsPerPage = 10, search = "" }) {
        const response = await fetch(`/api/people?page=${page}&itemsPerPage=${itemsPerPage}&search=${search}`);
        return await response.json();
    },

    async savePerson(payload) {
        const response = await fetch("/api/people", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw { status: response.status, errors: errorData.errors };
        }
        return await response.json();

    },

    async updatePerson(payload) {
        const response = await fetch(`/api/people/${payload.id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(payload),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw { status: response.status, errors: errorData.errors };
        }

        return await response.json();
    },

    async getPerson(personId) {
        const response = await fetch(`/api/people/${personId}`);
        return await response.json();
    },

    async deletePerson(personId) {
        const response = await fetch(`/api/people/${personId}`, {
            method: "DELETE",
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw { status: response.status, errors: errorData.errors };
        }
    },

    async getAddress(personId) {
        const response = await fetch(`/api/people/${personId}/active-address`);
        return await response.json();
    },

    async getHistoryAddress(personId) {
        const response = await fetch(`/api/people/${personId}/address-history`);
        return await response.json();
    },

    async createAddress(personId, address) {
        const response = await fetch(`/api/people/${personId}/addresses`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(address),
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw { status: response.status, errors: errorData.errors };
        }

        return await response.json();
    },

    async getAddressByZipCode(zipCode) {
        const response = await fetch(`https://viacep.com.br/ws/${zipCode}/json/`);
        return await response.json();
    },
};
