<script setup></script>

<template>
  <section class="layout-ui-date-badges">
    <Divider type="dashed" align="center">
      <h2>Badges</h2>
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
              <td>Default <br />xlarge</td>
              <td>
                <OverlayBadge value="35" severity="warn" size="xlarge">
                  <i-custom-notifications />
                </OverlayBadge>
              </td>
            </tr>
            <tr>
              <td>Default <br />large</td>
              <td>
                <OverlayBadge value="35" severity="success" size="large">
                  <i-custom-notifications />
                </OverlayBadge>
              </td>
            </tr>
            <tr>
              <td>Default <br />small</td>
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
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<style lang="scss" scoped></style>
