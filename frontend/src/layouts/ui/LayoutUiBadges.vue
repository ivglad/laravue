<script setup></script>

<template>
  <section class="layout-ui-badges">
    <Divider type="dashed" align="center">
      <h2>Badges / Avatar</h2>
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
                <OverlayBadge value="35" severity="danger">
                  <i-custom-notifications />
                </OverlayBadge>
              </td>
            </tr>
            <tr>
              <td>Xlarge</td>
              <td>
                <OverlayBadge value="35" severity="warn" size="xlarge">
                  <i-custom-notifications />
                </OverlayBadge>
              </td>
            </tr>
            <tr>
              <td>Large</td>
              <td>
                <OverlayBadge value="35" severity="success" size="large">
                  <i-custom-notifications />
                </OverlayBadge>
              </td>
            </tr>
            <tr>
              <td>Small</td>
              <td>
                <OverlayBadge value="35" severity="info" size="small">
                  <i-custom-notifications />
                </OverlayBadge>
              </td>
            </tr>
            <tr>
              <td>Dot</td>
              <td>
                <OverlayBadge severity="danger">
                  <i-custom-notifications />
                </OverlayBadge>
              </td>
            </tr>
            <tr>
              <td>Avatar</td>
              <td>
                <Avatar
                  :label="getInitialsHelper('Линус Бенедикт Торвальдс')"
                  size="xlarge"
                  shape="circle" />
              </td>
            </tr>
            <tr>
              <td>Avatar</td>
              <td>
                <OverlayBadge value="4" severity="danger">
                  <Avatar
                    :label="getInitialsHelper('Линус Бенедикт Торвальдс')"
                    size="xlarge"
                    shape="circle" />
                </OverlayBadge>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
