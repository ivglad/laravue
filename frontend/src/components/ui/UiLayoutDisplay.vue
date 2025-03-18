<script setup>
const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  states: {
    type: Array,
    default: () => ['Default', 'Disabled', 'Invalid'],
  },
  variants: {
    type: Array,
    default: () => [],
  },
})

// CSS Grid template с равными колонками
const gridTemplateColumns = computed(() => {
  // Первая колонка для заголовка вариантов, остальные равные для состояний
  return `minmax(150px, min-content) repeat(${props.states.length}, 1fr)`
})

/**
 * Форматирует строку для использования в имени слота
 * Преобразует строку в нижний регистр и заменяет пробелы на дефисы
 * @param {string} str - строка для форматирования
 * @return {string} форматированная строка
 */
const formatSlotName = (str) => {
  return str.toLowerCase().replace(/\s+/g, '-')
}

/**
 * Генерирует имя слота на основе варианта и состояния
 * @param {string} variant - название варианта
 * @param {string} state - название состояния
 * @return {string} имя слота
 */
const getSlotName = (variant, state) => {
  return `${formatSlotName(variant)}-${formatSlotName(state)}`
}
</script>

<template>
  <section class="ui-layout-display">
    <div class="ui-layout-display__header">
      <h2>{{ title }}</h2>
    </div>
    <div class="content">
      <div class="grid-table-wrapper">
        <!-- Заголовок таблицы -->
        <div class="grid-table-header">
          <div class="grid-cell header-cell first-cell">
            <span>Variant</span>
            <span>State:</span>
          </div>
          <div
            v-for="state in states"
            :key="state"
            class="grid-cell header-cell">
            {{ state }}
          </div>
        </div>

        <!-- Дополнительные варианты -->
        <div v-for="variant in variants" :key="variant" class="grid-table-row">
          <div class="grid-cell first-cell">{{ variant }}</div>
          <div v-for="state in states" :key="state" class="grid-cell">
            <slot :name="getSlotName(variant, state)"></slot>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
.ui-layout-display {
  border: 1px solid var(--p-surface-200);
  border-radius: var(--p-border-radius-lg);
  overflow: hidden;
  &__header {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 1rem;
    border-radius: var(--p-border-radius-lg) var(--p-border-radius-lg) 0 0;
    background-color: var(--p-surface-100);
    .h2 {
      @include fluid-text(24, 18);
      font-weight: 600;
    }
  }
}
.content {
  padding: 0 2rem 2rem 2rem;
}

.grid-table-wrapper {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  width: 100%;
  overflow-x: auto;

  .grid-table-header,
  .grid-table-row {
    display: grid;
    grid-template-columns: v-bind(gridTemplateColumns);
    gap: 1rem;
    width: 100%;
    @include mq(m) {
      grid-template-columns: 1fr;
      grid-auto-flow: row;
    }
  }
  .grid-table-row {
    @include mq(m) {
      margin-bottom: 1rem;
    }
  }

  .grid-table-header {
    border-top-left-radius: var(--p-border-radius-lg);
    border-top-right-radius: var(--p-border-radius-lg);

    .header-cell {
      font-weight: 600;

      &.first-cell {
        display: flex;
        justify-content: space-between;
        border-top-left-radius: var(--p-border-radius-lg);
        @include mq(m) {
          padding-inline: 1rem;
          border-top-right-radius: var(--p-border-radius-lg);
        }
        & span:last-child {
          @include mq(m) {
            display: none;
          }
        }
      }

      &:not(.first-cell) {
        @include mq(m) {
          display: none;
        }
      }

      &:last-child {
        border-top-right-radius: var(--p-border-radius-lg);
      }
    }
  }

  .grid-cell {
    display: flex;
    align-items: center;
    @include mq(m) {
      width: 100%;
      &:not(.first-cell):not(.header-cell) {
        grid-column: 1 / -1;
        padding-top: 0;
      }
      &.first-cell {
        font-weight: 600;
        background-color: var(--p-surface-100);
      }
      &.first-cell:not(.header-cell) {
        padding: 0.5rem 1rem;
      }
    }

    &.header-cell {
      min-height: 50px;
    }
    &.first-cell {
      text-align: start;
    }

    // Поддержка разрывов строк в заголовках
    &.header-cell,
    &.first-cell {
      white-space: pre-line;
    }
  }
}
</style>
