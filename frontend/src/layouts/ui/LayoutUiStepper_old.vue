<script setup></script>

<template>
  <section class="layout-ui-stepper">
    <Divider type="dashed" align="center">
      <h2>Stepper</h2>
    </Divider>
    <div class="content no-ui-styles">
      <Stepper :value="1" class="app-stepper">
        <StepList>
          <Step v-for="step in 3" :key="step" :value="step">
            Header {{ step }}
          </Step>
        </StepList>
        <StepPanels>
          <StepPanel
            v-for="step in 3"
            :key="step"
            v-slot="{ activateCallback }"
            :value="step">
            <div style="display: flex; padding: 2rem">
              <div>Content {{ step }}</div>
            </div>
            <div class="app-stepper-buttons">
              <Button
                :disabled="step === 1"
                label="Назад"
                outlined
                @click="activateCallback(step - 1)" />
              <Button
                :disabled="step === 3"
                label="Вперёд"
                outlined
                @click="activateCallback(step + 1)" />
            </div>
          </StepPanel>
        </StepPanels>
      </Stepper>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
