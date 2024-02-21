<script setup>
import { ref } from "vue";
import { vMaska } from "maska";
import { api } from "../services/api.js";


// Referências e Estados Reativos
const headers = ref([
    {
        title: "Nome",
        align: "start",
        sortable: false,
        value: "name",
    },
    { title: "CPF", value: "cpf" },
    { title: "Telefone", value: "phone" },
    { title: "Email", value: "email" },
    { title: "", value: "actions" },
]);
const serverItems = ref([]);
const totalItems = ref(0);
const loading = ref(false);
const search = ref("");
const itemsPerPage = ref(10);
const itemsPerPageOptions = [10, 15, 20];
const meta = ref([]);

const person = ref({
    name: "",
    social_name: "",
    cpf: "",
    father_name: "",
    mother_name: "",
    phone: "",
    email: "",
});
const errorsPerson = ref({
    name: "",
    social_name: "",
    cpf: "",
    father_name: "",
    mother_name: "",
    phone: "",
    email: "",
});
const personSelectedId = ref(0);


const address = ref({
    street: "",
    number: "",
    complement: "",
    type: "",
    neighborhood: "",
    city: "",
    state: "",
    zip_code: "",
    person_id: "",
});
const errorsAddress = ref({
    street: "",
    number: "",
    complement: "",
    type: "",
    neighborhood: "",
    city: "",
    state: "",
    zip_code: "",
});
const historyAddress = ref([]);


const resultAPiCep = ref({});


// Formulários e Erros de Formulários
const resetPersonForm = () => {
    person.value = {
        name: "",
        social_name: "",
        cpf: "",
        father_name: "",
        mother_name: "",
        phone: "",
        email: "",
    };
};
const resetErrorsPerson = () => {
    for (const key in errorsPerson.value) {
        if (errorsPerson.value.hasOwnProperty(key)) {
            errorsPerson.value[key] = "";
        }
    }
};
const resetAddressForm = () => {
    address.value = {
        street: "",
        number: "",
        complement: "",
        type: "",
        neighborhood: "",
        city: "",
        state: "",
        zip_code: "",
        person_id: "",
    };
};
const resetErrorsAddress = () => {
    for (const key in errorsAddress.value) {
        if (errorsAddress.value.hasOwnProperty(key)) {
            errorsAddress.value[key] = "";
        }
    }
};

// Dialogs/Modais
const dialog = ref(false);
const dialogDelete = ref(false);
const editDialog = ref(false);
const dialogOnlyRead = ref(false);
const dialogAddress = ref(false);
const dialogHistoryAddress = ref(false);



// Métodos de CRUD para person
const loadItems = async (params) => {
    loading.value = true;
    const data = await api.loadItems(params);
    serverItems.value = data.data;
    totalItems.value = data.meta.total;
    meta.value = data.meta;
    loading.value = false;
};
const openModal = () => {
    resetPersonForm();
    dialog.value = true;
    editDialog.value = false;
    dialogOnlyRead.value = false;
};
const savePerson = async () => {
    try {
        resetErrorsPerson();
        const personDetails = person.value;
        await api.savePerson(personDetails);

        dialog.value = false;
        resetPersonForm();
        await loadItems({ page: 1, itemsPerPage: 10, search: "" });
    } catch (error) {
        if (error.errors) {
            for (const [key, value] of Object.entries(error.errors)) {
                if (errorsPerson.value.hasOwnProperty(key)) {
                    errorsPerson.value[key] = value[0];
                }
            }
        }
    }
};
const updatePerson = async () => {
    resetErrorsPerson();
    const personDetails = person.value;

    try {
        await api.updatePerson(personDetails);

        dialog.value = false;
        resetPersonForm();
        await loadItems({ page: 1, itemsPerPage: 10, search: "" });
    } catch (error) {
        if (error.errors) {
            for (const [key, value] of Object.entries(error.errors)) {
                if (errorsPerson.value.hasOwnProperty(key)) {
                    errorsPerson.value[key] = value[0];
                }
            }
        }
    }
};
const getPerson = async (id) => {
    resetErrorsPerson();

    const personDetails = await api.getPerson(id);
    person.value = personDetails.data;
    dialog.value = true;
    editDialog.value = true;
};
const deletePerson = async () => {
    await api.deletePerson(person.value.id);
    dialogDelete.value = false;
    await loadItems({ page: 1, itemsPerPage: 10, search: "" });
};
const editPerson = async (id) => {
    dialogOnlyRead.value = false;
    getPerson(id);
};
const showPerson = async (id) => {
    dialogOnlyRead.value = true;
    getPerson(id);
};
const openDeleteModal = (personSelected) => {
    person.value = personSelected;
    dialogDelete.value = true;
};


// Métodos de CRUD para address
const openAddressModal = async (id) => {
    resetErrorsAddress();
    dialogAddress.value = true;
    personSelectedId.value = id;
    getAddress(id);

    resultAPiCep.value = {};
};
const openHistoryAddressModal = async (id) => {
    dialogHistoryAddress.value = true;
    getHistoryAddress();
};
const getAddress = async (id) => {
    const data = await api.getAddress(id);
    address.value = data.data;
};
const getHistoryAddress = async () => {
    const data = await api.getHistoryAddress(personSelectedId.value);
    historyAddress.value = data.data;
};
const createAddress = async () => {
    try {
        await api.createAddress( personSelectedId.value, address.value);
        dialogAddress.value = false;
        resetAddressForm();
    } catch (error) {
        if (error.errors) {
            for (const [key, value] of Object.entries(error.errors)) {
                if (errorsAddress.value.hasOwnProperty(key)) {
                    errorsAddress.value[key] = value[0];
                }
            }
        }
    }
};

// Métodos Auxiliares e de Formatação
const formatCpf = (value) => {
    if (!value) return "";
    return value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
};
const formatPhone = (value) => {
    if (!value) return "";
    return value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
};
const getAddressByZipCode = async (zipCode) => {

    try {
        var zipCodeOnlyNumbers = zipCode.replace(/\D/g, "");
        const response = await api.getAddressByZipCode(zipCodeOnlyNumbers);
        resultAPiCep.value = response;
        address.value = {
            street: response.logradouro,
            number: "",
            complement: response.complemento,
            neighborhood: response.bairro,
            city: response.localidade,
            state: response.uf,
            zip_code: zipCode,
            person_id: person.value.id,
        };
    } catch (error) {
        errorsAddress.value.zip_code = "CEP inválido";
    }
};

// Mascara de campos
const maskCpf = { mask: "###.###.###-##" };
const maskPhone = { mask: "(##) #####-####" };
const maskZipCode = { mask: "#####-###" };

</script>

<template>
    <v-layout>
        <v-app-bar app color="primary" dark>
            <v-toolbar-title>Cadastro de Pessoas</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="openModal" color="white" variant="outlined">
                <v-icon>mdi-account-plus</v-icon>
                <span class="hidden-sm-and-down">Nova Pessoa</span>
            </v-btn>
        </v-app-bar>

        <v-main>
            <v-container>
                <v-card>
                    <v-card-title>
                        <v-icon>mdi-account-circle</v-icon>
                        <span class="headline">Pessoas</span>
                    </v-card-title>
                    <v-card-text>
                        <v-row class="d-flex justify-end">
                            <v-col cols="4">
                                <v-text-field
                                    variant="outlined"
                                    v-model="search"
                                    label="Search"
                                    placeholder="Digite aqui ..."
                                    hint="Busque por nome, cpf, telefone ou email"
                                    append-icon="mdi-magnify"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-text>

                    <!-- Dialog for Creating/Editing Person -->
                    <v-dialog v-model="dialog" max-width="800px">
                        <v-card>
                            <v-card-title>
                                <v-icon left>mdi-account-circle</v-icon>
                                <span class="headline" v-if="!dialogOnlyRead">
                                    {{ editDialog ? "Atualizar" : "Criar" }}
                                    dados da Pessoa
                                </span>
                                <span class="headline" v-else>
                                    Detalhes da Pessoa
                                </span>
                            </v-card-title>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                variant="outlined"
                                                v-model="person.name"
                                                :error-messages="
                                                    errorsPerson.name
                                                "
                                                label="Name"
                                                required
                                                :readonly="dialogOnlyRead"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                variant="outlined"
                                                v-model="person.email"
                                                :error-messages="
                                                    errorsPerson.email
                                                "
                                                label="E-mail"
                                                required
                                                :readonly="dialogOnlyRead"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                variant="outlined"
                                                v-model="person.father_name"
                                                :error-messages="
                                                    errorsPerson.father_name
                                                "
                                                :counter="255"
                                                label="Father Name"
                                                required
                                                :readonly="dialogOnlyRead"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                variant="outlined"
                                                v-model="person.mother_name"
                                                :error-messages="
                                                    errorsPerson.mother_name
                                                "
                                                label="Mother Name"
                                                required
                                                :readonly="dialogOnlyRead"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field
                                                variant="outlined"
                                                v-model="person.phone"
                                                :error-messages="
                                                    errorsPerson.phone
                                                "
                                                :counter="255"
                                                label="Phone"
                                                required
                                                v-maska:[maskPhone]
                                                :readonly="dialogOnlyRead"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-text-field
                                                variant="outlined"
                                                v-model="person.cpf"
                                                :error-messages="
                                                    errorsPerson.cpf
                                                "
                                                :counter="11"
                                                label="CPF"
                                                v-maska:[maskCpf]
                                                required
                                                :readonly="dialogOnlyRead"
                                            ></v-text-field>
                                        </v-col>

                                        <v-col cols="12" sm="6">
                                            <v-text-field
                                                variant="outlined"
                                                v-model="person.social_name"
                                                :error-messages="
                                                    errorsPerson.social_name
                                                "
                                                label="Social Name"
                                                required
                                                :readonly="dialogOnlyRead"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="secondary"
                                    variant="flat"
                                    @click="dialog = false"
                                >
                                    Fechar
                                </v-btn>
                                <v-btn
                                    v-if="!dialogOnlyRead"
                                    color="primary"
                                    variant="flat"
                                    @click="
                                        editDialog
                                            ? updatePerson()
                                            : savePerson()
                                    "
                                >
                                    {{
                                        editDialog ? "Atualizar" : "Salvar"
                                    }}</v-btn
                                >
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Dialog for Deleting Person -->
                    <v-dialog v-model="dialogDelete" max-width="800px">
                        <v-card>
                            <v-card-title>
                                <v-icon left>mdi-account-circle</v-icon>
                                <span class="headline">Delete Person</span>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12">
                                        <p>
                                            Voçê tem certeza que deseja excluir
                                            essa pessoa?
                                            <b>{{ person.name }}</b>
                                        </p>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-actions
                                class="justify-center border-0 background-g"
                                color="primary"
                            >
                                <v-spacer></v-spacer>

                                <v-btn
                                    color="secondary"
                                    variant="flat"
                                    @click="dialogDelete = false"
                                    >Fechar</v-btn
                                >
                                <v-btn
                                    variant="flat"
                                    color="primary"
                                    @click="deletePerson()"
                                    >Delete</v-btn
                                >
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Dialog for Address -->
                    <v-dialog v-model="dialogAddress" max-width="800px">
                        <v-card>
                            <v-card-title>
                                <v-icon left>mdi-map-marker</v-icon>
                                <span class="headline">Endereço</span>
                            </v-card-title>
                            <v-card-text>
                                <v-row>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            variant="outlined"
                                            v-model="address.zip_code"
                                            label="CEP"
                                            required
                                            v-maska:[maskZipCode]
                                            :error-messages="
                                                errorsAddress.zip_code
                                            "
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-btn
                                            color="primary"
                                            size="large"
                                            @click="
                                                getAddressByZipCode(
                                                    address.zip_code
                                                )
                                            "
                                        >
                                            <v-icon>mdi-magnify</v-icon>
                                        </v-btn>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="12">
                                        <v-text-field
                                            variant="outlined"
                                            v-model="address.street"
                                            label="Rua"
                                            required
                                            :readonly="
                                                !!resultAPiCep.logradouro
                                            "
                                            :error-messages="
                                                errorsAddress.street
                                            "
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            variant="outlined"
                                            v-model="address.number"
                                            label="Número"
                                            type="number"
                                            required
                                            :error-messages="
                                                errorsAddress.number
                                            "
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            variant="outlined"
                                            v-model="address.complement"
                                            label="Complemento"
                                            required
                                            :readonly="!!resultAPiCep.bairro"
                                            :error-messages="
                                                errorsAddress.complement
                                            "
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            variant="outlined"
                                            v-model="address.neighborhood"
                                            label="Bairro"
                                            required
                                            :readonly="!!resultAPiCep.bairro"
                                            :error-messages="
                                                errorsAddress.neighborhood
                                            "
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            variant="outlined"
                                            v-model="address.city"
                                            label="Cidade"
                                            required
                                            :readonly="
                                                !!resultAPiCep.localidade
                                            "
                                            :error-messages="errorsAddress.city"
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <v-text-field
                                            variant="outlined"
                                            v-model="address.state"
                                            label="Estado"
                                            required
                                            :readonly="!!resultAPiCep.uf"
                                            :error-messages="
                                                errorsAddress.state
                                            "
                                        ></v-text-field>
                                    </v-col>

                                    <v-col cols="12" sm="6">
                                        <v-select
                                            variant="outlined"
                                            v-model="address.type"
                                            :items="[
                                                {
                                                    title: 'Residencial',
                                                    value: 'residential',
                                                },
                                                {
                                                    title: 'Comercial',
                                                    value: 'commercial',
                                                },
                                            ]"
                                            label="Tipo"
                                            required
                                            :error-messages="errorsAddress.type"
                                        ></v-select>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-actions
                                class="justify-center border-0 background-g"
                                color="primary"
                            >
                                <v-btn
                                    color="info"
                                    variant="flat"
                                    @click="openHistoryAddressModal(person.id)"
                                    >Histórico de Endereços</v-btn
                                >
                                <v-spacer></v-spacer>

                                <v-btn
                                    color="secondary"
                                    variant="flat"
                                    @click="dialogAddress = false"
                                    >Cancel</v-btn
                                >

                                <v-btn
                                    variant="flat"
                                    color="primary"
                                    @click="createAddress()"
                                >
                                    Salvar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <!-- Dialog for History Address -->
                    <v-dialog v-model="dialogHistoryAddress">
                        <v-card>
                            <v-card-title>
                                <v-icon left>mdi-map-marker</v-icon>
                                <span class="headline"
                                    >Histórico de Endereços</span
                                >
                            </v-card-title>
                            <v-card-text>
                                <v-table>
                                    <template v-slot:default>
                                        <thead class="background border-0">
                                            <tr>
                                                <th class="text-left">Tipo</th>
                                                <th class="text-left">CEP</th>
                                                <th class="text-left">Rua</th>
                                                <th class="text-left">
                                                    Complemento
                                                </th>
                                                <th class="text-left">
                                                    Bairro
                                                </th>
                                                <th class="text-left">
                                                    Cidade/Estado
                                                </th>
                                                <th class="text-left">Ativo</th>
                                                <th class="text-left">
                                                    Data de alteração
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="item in historyAddress"
                                                :key="item.id"
                                            >
                                                <td class="text-left">
                                                    <v-badge
                                                        color="warning"
                                                        content="Residencial"
                                                        v-if="
                                                            item.type ===
                                                            'residential'
                                                        "
                                                    ></v-badge>
                                                    <v-badge
                                                        color="info"
                                                        content="Comercial"
                                                        v-else
                                                    ></v-badge>
                                                </td>
                                                <td>{{ item.zip_code }}</td>
                                                <td>
                                                    {{ item.street }}, Nº
                                                    {{ item.number }}
                                                </td>
                                                <td>{{ item.complement }}</td>
                                                <td>{{ item.neighborhood }}</td>
                                                <td>
                                                    {{ item.city }} /
                                                    {{ item.state }}
                                                </td>

                                                <td>
                                                    <v-badge
                                                        color="primary"
                                                        content="Ativo"
                                                        v-if="item.is_active"
                                                    ></v-badge>
                                                    <v-badge
                                                        color="error"
                                                        content="Inativo"
                                                        v-else
                                                    ></v-badge>
                                                </td>
                                                <td>
                                                    {{
                                                        new Date(
                                                            item.updated_at
                                                        ).toLocaleString()
                                                    }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-table>
                            </v-card-text>
                            <v-card-actions
                                class="justify-center border-0 background-g"
                                color="primary"
                            >
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="secondary"
                                    variant="flat"
                                    @click="dialogHistoryAddress = false"
                                    >Fechar</v-btn
                                >
                            </v-card-actions>
                        </v-card>
                    </v-dialog>


                    <v-data-table-server
                        v-model:items-per-page="itemsPerPage"
                        :headers="headers"
                        :items-length="meta.total"
                        :items="serverItems"
                        :loading="loading"
                        :search="search"
                        @update:options="loadItems"
                        :items-per-page-options="itemsPerPageOptions"
                    >
                        <template v-slot:[`item.actions`]="{ item }">
                            <div
                                class="text-center d-flex justify-center gap-2"
                            >
                                <v-btn
                                    color="primary"
                                    @click="showPerson(item.id)"
                                    size="small"
                                >
                                    <v-icon>mdi-eye</v-icon>
                                </v-btn>
                                <v-btn
                                    color="warning"
                                    @click="editPerson(item.id)"
                                    size="small"
                                >
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn
                                    @click="openDeleteModal(item)"
                                    color="error"
                                    size="small"
                                >
                                    <v-icon>mdi-delete</v-icon>
                                </v-btn>

                                <v-btn
                                    color="info"
                                    @click="openAddressModal(item.id)"
                                    size="small"
                                >
                                    <v-icon>mdi-map-marker</v-icon>
                                </v-btn>
                            </div>
                        </template>

                        <template v-slot:[`item.cpf`]="{ item }">
                            {{ formatCpf(item.cpf) }}
                        </template>

                        <template v-slot:[`item.phone`]="{ item }">
                            {{ formatPhone(item.phone) }}
                        </template>
                    </v-data-table-server>
                </v-card>
            </v-container>
        </v-main>

        <!-- Footer -->
        <v-footer color="primary" app dark>
            <span class="white--text">&copy; 2021</span>
        </v-footer>
    </v-layout>
</template>
<style>
@keyframes slideInFromRight {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(0);
    }
}

.custom-dialog-transition-enter-active,
.custom-dialog-transition-leave-active {
    animation: slideInFromRight 0.5s;
}

.custom-dialog-transition-leave-active {
    animation: slideInFromRight 0.5s reverse;
}
</style>
