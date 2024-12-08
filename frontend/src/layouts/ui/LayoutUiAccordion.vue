<script setup>
const panelIconStyle = (collapsed) => {
  if (!collapsed) {
    return {
      transform: 'rotate(45deg)',
    }
  }
}
</script>

<template>
  <section class="layout-ui-accordion">
    <Divider type="dashed" align="center">
      <h2>Accordion / Panel / Fieldset</h2>
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
              <td>Accordion</td>
              <td>
                <Accordion :value="['0']" multiple>
                  <AccordionPanel value="0">
                    <AccordionHeader>Заголовок I</AccordionHeader>
                    <AccordionContent>
                      <p class="m-0">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua. Ut enim ad minim veniam, quis nostrud
                        exercitation ullamco laboris nisi ut aliquip ex ea
                        commodo consequat. Duis aute irure dolor in
                        reprehenderit in voluptate velit esse cillum dolore eu
                        fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                        non proident, sunt in culpa qui officia deserunt mollit
                        anim id est laborum.
                      </p>
                    </AccordionContent>
                  </AccordionPanel>
                  <AccordionPanel value="1">
                    <AccordionHeader>Заголовок II</AccordionHeader>
                    <AccordionContent>
                      <p class="m-0">
                        Sed ut perspiciatis unde omnis iste natus error sit
                        voluptatem accusantium doloremque laudantium, totam rem
                        aperiam, eaque ipsa quae ab illo inventore veritatis et
                        quasi architecto beatae vitae dicta sunt explicabo. Nemo
                        enim ipsam voluptatem quia voluptas sit aspernatur aut
                        odit aut fugit, sed quia consequuntur magni dolores eos
                        qui ratione voluptatem sequi nesciunt. Consectetur,
                        adipisci velit, sed quia non numquam eius modi.
                      </p>
                    </AccordionContent>
                  </AccordionPanel>
                  <AccordionPanel value="2" disabled>
                    <AccordionHeader>Заголовок III</AccordionHeader>
                    <AccordionContent>
                      <p class="m-0">
                        At vero eos et accusamus et iusto odio dignissimos
                        ducimus qui blanditiis praesentium voluptatum deleniti
                        atque corrupti quos dolores et quas molestias excepturi
                        sint occaecati cupiditate non provident, similique sunt
                        in culpa qui officia deserunt mollitia animi, id est
                        laborum et dolorum fuga. Et harum quidem rerum facilis
                        est et expedita distinctio. Nam libero tempore, cum
                        soluta nobis est eligendi optio cumque nihil impedit quo
                        minus.
                      </p>
                    </AccordionContent>
                  </AccordionPanel>
                </Accordion>
              </td>
            </tr>
            <tr>
              <td>Panel</td>
              <td>
                <Panel header="Заголовок" toggleable>
                  <template #toggleicon="{ collapsed }">
                    <Transition>
                      <i-custom-plus :style="panelIconStyle(collapsed)" />
                    </Transition>
                  </template>
                  <p class="m-0">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                    do eiusmod tempor incididunt ut labore et dolore magna
                    aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                    ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit
                    esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                    occaecat cupidatat non proident, sunt in culpa qui officia
                    deserunt mollit anim id est laborum.
                  </p>
                </Panel>
              </td>
            </tr>
            <tr>
              <td>Fieldset</td>
              <td>
                <Fieldset legend="Заголовок" toggleable>
                  <template #toggleicon="{ collapsed }">
                    <Transition>
                      <i-custom-plus :style="panelIconStyle(collapsed)" />
                    </Transition>
                  </template>
                  <p class="m-0">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                    do eiusmod tempor incididunt ut labore et dolore magna
                    aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                    ullamco laboris nisi ut aliquip ex ea commodo consequat.
                    Duis aute irure dolor in reprehenderit in voluptate velit
                    esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                    occaecat cupidatat non proident, sunt in culpa qui officia
                    deserunt mollit anim id est laborum.
                  </p>
                </Fieldset>
              </td>
            </tr>
            <tr>
              <td>ScrollPanel</td>
              <td>
                <ScrollPanel class="scrollpanel">
                  <span class="fw-semibold">ScrollPanel</span>
                  <p>
                    ScrollPanel is a cross browser, lightweight and skinnable
                    alternative to native browser scrollbar. <br /><br />Lorem
                    ipsum dolor sit amet, consectetur adipiscing elit, sed do
                    eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco
                    laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                    irure dolor in reprehenderit in voluptate velit esse cillum
                    dolore eu fugiat nulla pariatur. Excepteur sint occaecat
                    cupidatat non proident, sunt in culpa qui officia deserunt
                    mollit anim id est laborum.
                  </p>
                </ScrollPanel>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped>
.content {
  display: flex;
  flex-direction: column;
  gap: $size-10;
  .scrollpanel {
    width: 100%;
    height: 8rem;
  }
}
</style>
