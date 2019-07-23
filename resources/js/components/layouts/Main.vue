<template>
  <div id="app">
    <el-container style="height: 500px; border: 1px solid #eee;direction:rtl">
      <side-bar></side-bar>
      <main-content></main-content>
    </el-container>
  </div>
</template>
<script>
import SideBar from './Sidebar.vue';
import Main from './body.vue';
export default {
  components: {
        'side-bar' : SideBar,
        'main-content' : Main,
    }
}
</script>
<style scoped>
  /* Add scoped styles */
  #app {
    padding: 20px;
  }
</style>
