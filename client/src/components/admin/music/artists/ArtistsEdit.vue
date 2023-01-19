<template>
  <div class="q-mb-md">
    Всего исполнителей: <b>{{ total }}</b>
  </div>
  <div class="artists-search q-mb-md">
    <q-input v-model="search" @keyup.enter="searchArtists(search)" label="Поиск" maxlength="12" dense outlined bottom-slots counter>
      <template v-slot:append>
        <q-icon v-if="search !== ''" name="close" @click="search = ''" class="cursor-pointer" />
      </template>
      <template v-slot:after>
        <q-icon name="search" @click="searchArtists(search)" class="cursor-pointer" />
      </template>
    </q-input>
  </div>
  <div class="artists-list">
    <q-table
      :rows="artists"
      :columns="columns"
      row-key="name"
      :flat="true"
      :rows-per-page-options="[0]"
      :pagination.sync="{page: 1, rowsPerPage: 0}"
    >
      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th
            v-for="col in props.cols"
            :key="col.name"
            :props="props"
          >
            {{ col.label }}
          </q-th>
          <q-th auto-width />
        </q-tr>
      </template>
      <template v-slot:body="props">
        <q-tr :props="props" class="artist-row">
          <q-td
            v-for="col in props.cols"
            :key="col.name"
            :props="props"
          >
            <div v-if="col.name === 'image'" class="artist-row__image">
              <img :src="col.value" :alt="col.value">
            </div>
            <div v-else-if="col.name === 'tags'" class="artist-row__tags">
              <div class="artist-row__tag"><span v-for="tag in col.value.common">{{ tag.label }}</span></div>
              <div class="artist-row__tag"><span v-for="tag in col.value.secondary">{{ tag.label }}</span></div>
            </div>
            <span v-else>{{ col.value }}</span>
          </q-td>
          <q-td class="q-gutter-x-sm" auto-width>
            <q-btn size="sm" @click="editArtist(props.row)" label="Редактировать" />
            <q-btn size="sm" @click="deleteArtist(props.row)" label="Удалить" color="red" />
          </q-td>
        </q-tr>
      </template>
    </q-table>
    <q-dialog v-model="showModal">
      <q-card style="width: 700px; max-width: 80vw;">
        <q-card-section>
          <div class="text-h6">Редактирование исполнителя</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form @submit="onSubmit" class=" q-gutter-y-md column">
            <q-input v-model="model.name" label="Название исполнителя" outlined />
            <q-input v-model="model.description" label="Описание исполнителя" type="textarea" outlined />
            <q-file v-model="model.image" label="Постер" name="poster" filled>
              <template v-if="model.image" v-slot:append>
                <q-icon name="cancel" @click.stop.prevent="model.image = null" class="cursor-pointer" />
              </template>
            </q-file>
            <div class="artist-edit__image">
              <img v-if="model.image" :src="model.image" alt="">
            </div>
            <q-select
              label="Основные жанры"
              v-model="model.tags.common"
              input-debounce="0"
              :options="commonTags"
              use-input
              use-chips
              multiple
              outlined
            />
            <q-select
              label="Дополнительные жанры"
              v-model="model.tags.secondary"
              input-debounce="0"
              :options="secondaryTags"
              use-input
              use-chips
              multiple
              outlined
            />
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="bg-white text-teal">
          <q-btn label="Сохранить" @click="saveArtist" flat />
          <q-btn label="Отмена" flat v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>
<script>
import {ref} from 'vue'
import API from "src/utils/api";
import { useQuasar } from 'quasar'

export default {
  setup() {
    const $q = useQuasar()
    const total = ref(0)
    const search = ref('')
    const columns = ref([{
      name: "id",
      required: true,
      label: 'ID',
      align: 'left',
      field: row => row.id,
      sortable: true,
      style: 'width: 40px'
    },{
      name: "image",
      required: true,
      label: 'Изображение',
      align: 'center',
      field: row => row.image,
      sortable: false,
      style: 'width: 60px'
    },{
      name: "name",
      required: true,
      label: 'Имя',
      align: 'left',
      field: row => row.name,
      sortable: true
    },{
      name: "tags",
      required: true,
      label: 'Теги',
      align: 'center',
      field: row => row.tags,
      sortable: false,
    }, {
      name: "createdAt",
      required: true,
      label: 'Дата добавления',
      align: 'left',
      field: row => row.createdAt,
      sortable: true
    }])
    const artists = ref([])
    let showModal = ref(false)
    const commonTags = ref([])
    const secondaryTags = ref([])
    let model = ref({
      name: null,
      description: null,
      image: null,
      tags: {
        common: null,
        secondary: null
      }
    })
    const getArtists = async (page) => {
      const {data} = await API.post('music/admin/artists/get', {page: page})
      artists.value = data.data.artists
      total.value = data.data.total
    }
    const getTagsSelect = async () => {
      const {data} = await API.post('music/tags/select')
      commonTags.value = Object.keys(data.tags.common).map(key => data.tags.common[key])
      secondaryTags.value = Object.keys(data.tags.secondary).map(key => data.tags.secondary[key])
    }
    const searchArtists = async (search) => {
      const {data} = await API.post('music/admin/artists/search', {name: search})
      artists.value = data.data.artists
    }
    const editArtist = async (artist) => {
      model.value.name = artist.name
      model.value.description = artist.description
      model.value.image = artist.image
      model.value.tags.common = artist.tags.common
      model.value.tags.secondary = artist.tags.secondary

      showModal.value = true
      // const {data} = await API.post('music/admin/artists/search', {name: search})
      // artists.value = data.data.artists
    }
    const deleteArtist = (artist) => {
      $q.notify({
        type: 'positive',
        message: 'Вы успешно вошли в систему!'
      });
    }
    const saveArtist = async () => {
      console.log(model.value.tags.common)
    }

    return {
      total,
      search,
      columns,
      artists,
      showModal,
      model,
      commonTags,
      secondaryTags,
      getArtists,
      getTagsSelect,
      searchArtists,
      editArtist,
      saveArtist
    }
  },
  mounted() {
    this.getArtists()
    this.getTagsSelect()
  }
}
</script>
<style lang="scss" scoped>
.artist {
  &-edit {
    &__image {
      width: 250px;
      height: 250px;

      img {
        width: 100%;
        height: 100%;
      }
    }
  }
  &-row {
    &__image {
      width: 50px;
      height: 50px;

      img {
        width: 100%;
        object-fit: cover;
      }
    }
    &__tag {
      & span:not(:last-child) {
        &::after {
          content: ', '
        }
      }
    }
  }
  &-search {
    max-width: 400px;
  }
}
</style>
