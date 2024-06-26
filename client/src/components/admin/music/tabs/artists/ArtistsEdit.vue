<template>
  <div class="text-h6 q-mb-md">Редактирование исполнителей</div>
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
            <q-btn size="sm" @click="initArtistEdit(props.row)" label="Редактировать" />
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
          <q-form class="q-gutter-y-md column">
            <q-input
              v-model="model.name"
              label="Название исполнителя"
              :rules="[ val => val && val.length > 0 || 'Поле name должно быть заполнено!']"
              outlined
            />
            <q-input v-model="model.content" label="Описание исполнителя" type="textarea" outlined />
            <q-file v-model="newImage" label="Постер" name="poster" filled>
              <template v-if="model.image" v-slot:append>
                <q-icon name="cancel" @click.stop.prevent="model.image = null" class="cursor-pointer" />
              </template>
            </q-file>
            <div v-if="newImage" class="artist-edit__image">
              <img :src="newImagePreview" alt="">
            </div>
            <div v-else-if="!newImage && typeof model.image === 'string'" class="artist-edit__image">
              <img :src="model.image" alt="">
            </div>
            <q-select
              label="Основные жанры"
              v-model="model.tags.common"
              @filter="commonTagsFilter"
              input-debounce="0"
              :options="commonOptions"
              use-input
              use-chips
              multiple
              outlined
            />
            <q-select
              label="Дополнительные жанры"
              v-model="model.tags.secondary"
              @filter="secondaryTagsFilter"
              input-debounce="0"
              :options="secondaryOptions"
              use-input
              use-chips
              multiple
              outlined
            />
          </q-form>
        </q-card-section>

        <q-card-actions align="right" class="bg-white">
          <q-btn label="Сохранить" color="primary" @click="updateArtist" :loading="updateButtonLoading" />
          <!--Todo: Need to write cancel update handler for returning previous values to model-->
          <q-btn label="Отмена" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>
<script>
import { computed, ref } from "vue"
import { useQuasar } from "quasar"
import { api } from "boot/axios"

export default {
  setup() {
    const $q = useQuasar()

    const columns = ref([{
      name: 'id',
      required: true,
      label: 'ID',
      align: 'left',
      field: row => row.id,
      sortable: true,
      style: 'width: 40px'
    }, {
      name: 'image',
      required: true,
      label: 'Изображение',
      align: 'center',
      field: row => row.image,
      sortable: false,
      style: 'width: 60px'
    }, {
      name: 'name',
      required: true,
      label: 'Имя',
      align: 'left',
      field: row => row.name,
      sortable: true
    }, {
      name: 'tags',
      required: true,
      label: 'Теги',
      align: 'center',
      field: row => row.tags,
      sortable: false,
    }, {
      name: 'createdAt',
      required: true,
      label: 'Дата добавления',
      align: 'left',
      field: row => row.createdAt,
      sortable: true
    }])
    const total = ref(0)
    const search = ref('')
    const artists = ref([])
    const commonTags = ref([])
    const secondaryTags = ref([])
    const commonOptions = ref(commonTags.value)
    const secondaryOptions = ref(secondaryTags.value)
    const newImage = ref(null)
    let showModal = ref(false)
    let updateButtonLoading = ref(false)
    const newImagePreview = computed(() => {
      model.value.image = newImage.value
      return URL.createObjectURL(newImage.value)
    })
    let model = ref({
      id: 0,
      name: null,
      content: null,
      image: null,
      tags: {
        common: [],
        secondary: []
      }
    })

    const getArtists = async page => {
      const {data} = await api.post('music/admin/artists', {page: page})
      artists.value = data.data.artists
      total.value = data.data.total
    }
    const getTagsSelect = async () => {
      const {data: {data}} = await api.post('music/tags/select')

      commonTags.value = Object.keys(data.items.common).map(key => data.items.common[key])
      secondaryTags.value = Object.keys(data.items.secondary).map(key => data.items.secondary[key])
    }
    const searchArtists = async search => {
      const {data} = await api.post('music/admin/artists/search', {name: search})
      artists.value = data.data.artists
    }
    const initArtistEdit = artist => {
      model.value = artist

      showModal.value = true
    }
    const commonTagsFilter = (val, update) => {
      update(() => {
        const needle = val.toLowerCase()
        commonOptions.value = commonTags.value.filter(tag => tag.label.toLowerCase().indexOf(needle) > -1)
      })
    }
    const secondaryTagsFilter = (val, update) => {
      update(() => {
        const needle = val.toLowerCase()
        secondaryOptions.value = commonTags.value.filter(tag => tag.label.toLowerCase().indexOf(needle) > -1)
      })
    }
    const deleteArtist = (artist) => {
      $q.dialog({
        title: 'Confirm',
        message: `Удалить исполнителя ${artist.name} и все его альбомы?`,
        cancel: true,
        persistent: true
      }).onOk(() => {
        //todo Add method to delete artist
      })
    }
    const updateArtist = async () => {
      updateButtonLoading.value = true

      const formData = new FormData();
      formData.append('name', model.value.name)
      formData.append('content', model.value.content)

      if (typeof model.value.image === 'object') {
        formData.append('image', model.value.image)
      }

      let commonTagsValues = model.value.tags.common.map(item => item.value)
      let secondaryTagsValues = model.value.tags.secondary.map(item => item.value)

      commonTagsValues.concat(secondaryTagsValues).forEach(val => {
        formData.append('tags[]', val)
      });

      await api.post(`music/admin/artists/${model.value.id}/update`, formData)
        .then(response => {
          for(let key in artists.value) {
            if(artists.value[key].id === response.data.data.id) {
              artists.value[key] = response.data.data
            }
          }

          $q.notify({
            type: 'positive',
            message: response.data.message
          })

        }).catch(error => {
          $q.notify({
            type: 'negative',
            message: error.response.data.message
          })
        }).finally(() => {
          updateButtonLoading.value = false
        })
    }

    return {
      total,
      search,
      columns,
      artists,
      showModal,
      model,
      newImage,
      commonTags,
      secondaryTags,
      commonOptions,
      secondaryOptions,
      updateButtonLoading,
      newImagePreview,
      commonTagsFilter,
      secondaryTagsFilter,
      getArtists,
      getTagsSelect,
      searchArtists,
      initArtistEdit,
      updateArtist,
      deleteArtist
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
      overflow: hidden;

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
