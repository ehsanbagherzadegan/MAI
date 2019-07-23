<template>
<el-container>
  <el-aside width="100%" style="background-color: rgb(238, 241, 246);direction:rtl;text-align:justify;padding-left:1px;">
    <el-menu default-active="0" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" :collapse="isCollapse" background-color="#545c64"
      text-color="#fff"
      active-text-color="#ffd04b">
		<el-menu-item style="padding-right:0px !important">
			<img v-if="!isCollapse" :src="'/img/radanitLogo.png'" width="50" height="50" alt="">
			<img v-if="isCollapse" :src="'/img/radanitLogo.png'" width="30" height="30" alt="">
			<span slot="title">سامانه یکپارچه رادان</span>
	  </el-menu-item>
		<el-menu-item id="collapse-menu" index="0" @click="collapse()">
			<i id="collapse-icon" v-bind:class="[isCollapse ? 'el-icon-s-fold':'el-icon-s-unfold']" ></i>
			<span slot="title">نگهداری و تعمیرات</span>
	  </el-menu-item>		
	  <el-submenu index="1">
		<template slot="title">
		  <i class="el-icon-location"></i>
		  <span slot="title">Navigator One</span>
		</template>
		<el-menu-item-group>
		  <span slot="title">Group One</span>
		  <el-menu-item index="1-1">item one</el-menu-item>
		  <el-menu-item index="1-2">item two</el-menu-item>
		</el-menu-item-group>
		<el-menu-item-group title="Group Two">
		  <el-menu-item index="1-3">item three</el-menu-item>
		</el-menu-item-group>
	  </el-submenu>
	  <el-menu-item index="2">
		<i class="el-icon-menu"></i>
		<span slot="title">Navigator Two</span>
	  </el-menu-item>
	  <el-menu-item index="3" disabled>
		<i class="el-icon-document"></i>
		<span slot="title">Navigator Three</span>
	  </el-menu-item>
	  <el-menu-item index="4">
		<i class="el-icon-setting"></i>
		<span slot="title">Navigator Four</span>
	  </el-menu-item>
	</el-menu>
  </el-aside>
</el-container>
</template>
<style>
  .el-menu-vertical-demo:not(.el-menu--collapse) {
    width: 200px;
    min-height: 400px;
  }
  /* changed by e.bagherzadegan */
  .el-menu{
	direction:rtl;
	padding-left:auto !important;
	padding-right:0px;
	font-family: Vazir;
  }
  .el-submenu__title{
	padding: 0 20px !important;

  }
  .el-submenu__icon-arrow{
	right:auto !important;
	left:20px;
  }
  .el-menu-item-group__title{
	padding:7px 45px 7px 0 !important;
  }
  .el-menu--collapse{
	float:right;
    width: 64px !important;
  }
  #collapse-menu{
		color: dimgrey;		
	}
	#collapse-menu:hover{
		color: dimgrey;
		background:none;
		background-color: #ffffff;
	}
	/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #555; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #ecf5ff; 
}
</style>


<script>
  export default {
    data() {
      return {
		    isCollapse: false
      }
    },
	methods: {
        handleOpen(key, keyPath) {
        console.log(key, keyPath);
      },
      handleClose(key, keyPath) {
        console.log(key, keyPath);
      },
	  collapse(){
		if(this.isCollapse)
			this.isCollapse = false;
		else
			this.isCollapse = true;
	  }
    }
  };
</script>