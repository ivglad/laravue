<script setup>
const toast = useToast()
const onUpload = () => {
  toast.add({
    severity: 'success',
    summary: 'Успешно',
    detail: 'Файл загружен',
    life: 3000,
  })
}
const onErrorUpload = (e) => {
  upsertUploadErrorFiles(e.files)
  toast.add({
    severity: 'error',
    summary: 'Ошибка',
    detail: 'Не удалось загрузить файл',
    life: 3000,
  })
}

const uploadErrorFiles = ref([])
const isFileUploadingError = (file) => {
  return uploadErrorFiles.value.find(
    (uploadFile) => uploadFile.objectURL === file.objectURL,
  )
}
const upsertUploadErrorFiles = (files) => {
  uploadErrorFiles.value.push(...files)
}

const onRemoveFile = (file, removeFileCallback, index) => {
  removeFileCallback(index)
}
const uploadEvent = (callback) => {
  callback()
}
</script>

<template>
  <section class="layout-ui-date-fileupload">
    <Divider type="dashed" align="center">
      <h2>File upload</h2>
    </Divider>
    <div class="content">
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>State /<br />Variant</th>
              <th>Default</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Default</td>
              <td>
                <FileUpload
                  name="requestParameter[]"
                  url="/api/upload"
                  pt:root:class="app-fileupload"
                  pt:header:class="app-fileupload-header"
                  pt:content:class="app-fileupload-content"
                  pt:empty:class="app-fileupload-empty"
                  invalidFileSizeMessage="Размер файла не должен превышать 1 МБ"
                  invalidFileLimitMessage="Количество загружаемых файлов не должно превышать 10"
                  invalidFileTypeMessage="Неправильный тип файла"
                  :fileLimit="10"
                  accept="image/*"
                  :maxFileSize="1000000"
                  multiple
                  :showCancelButton="false"
                  @upload="onUpload($event)"
                  @error="onErrorUpload($event)">
                  <template #empty>
                    <span
                      >Перетащите файлы сюда <br />или выберите вручную</span
                    >
                  </template>
                  <template #header="{ chooseCallback, uploadCallback, files }">
                    <Button label="Прикрепить" @click="chooseCallback()" />
                    <Button
                      label="Загрузить"
                      :disabled="!files || files.length === 0"
                      @click="uploadEvent(uploadCallback)" />
                  </template>
                  <template
                    #content="{
                      files,
                      uploadedFiles,
                      removeUploadedFileCallback,
                      removeFileCallback,
                      messages,
                    }">
                    <Message
                      v-for="message of messages"
                      :key="message"
                      size="small"
                      severity="error">
                      {{ message }}
                    </Message>
                    <div v-if="files.length > 0" class="app-fileupload-files">
                      <div
                        class="app-fileupload-file"
                        v-for="(file, index) of files"
                        :key="file.name + file.type + file.size">
                        <div class="app-fileupload-file-info">
                          <span
                            class="app-fileupload-file-name"
                            v-tooltip.top="{
                              value: file.name,
                              showDelay: 500,
                            }">
                            {{ file.name }}
                          </span>
                          <Message
                            severity="secondary"
                            variant="simple"
                            size="small"
                            >{{ formatSizeHelper(file.size) }}</Message
                          >
                        </div>
                        <Button
                          variant="text"
                          severity="secondary"
                          rounded
                          @click="
                            onRemoveFile(file, removeFileCallback, index)
                          ">
                          <template #icon>
                            <i-custom-close />
                          </template>
                        </Button>
                      </div>
                    </div>
                    <div v-if="uploadedFiles.length > 0">
                      <div
                        class="app-fileupload-file"
                        v-for="(file, index) of uploadedFiles"
                        :key="file.name + file.type + file.size">
                        <div class="app-fileupload-file-info">
                          <span
                            class="app-fileupload-file-name"
                            v-tooltip.top="{
                              value: file.name,
                              showDelay: 500,
                            }">
                            {{ file.name }}
                          </span>
                          <Message
                            severity="secondary"
                            variant="simple"
                            size="small"
                            >{{ formatSizeHelper(file.size) }}</Message
                          >
                        </div>
                        
                        <Badge
                          v-if="isFileUploadingError(file)"
                          value="Ошибка"
                          severity="danger"
                          size="small" />

                        <Button
                          variant="text"
                          severity="secondary"
                          rounded
                          @click="
                            removeUploadedFileCallback(
                              file,
                              removeFileCallback,
                              index,
                            )
                          ">
                          <template #icon>
                            <i-custom-close />
                          </template>
                        </Button>
                      </div>
                    </div>
                  </template>
                </FileUpload>
              </td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
