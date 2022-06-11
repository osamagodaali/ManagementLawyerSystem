<template>
    <div class="dropdown nav-item main-header-notification">
        <a class="new nav-link" href="#"><span v-if="unreadCount > 0">{{unreadCount}}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            </svg><span class=" pulse"></span></a>
        <div class="dropdown-menu">
            <div class="menu-header-content bg-primary text-right">
                <div class="d-flex">
                    <h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">Notifications</h6>
                    <span class="badge badge-pill badge-warning mr-auto my-auto float-left">Mark All Read</span>
                </div>
                <p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 " v-if="unreadCount > 0" >You have {{unreadCount}} unread Notifications
                </p>
            </div>
            <div class="main-notification-list Notification-scroll" v-if="unreadCount > 0">
                <a class="d-flex p-3 border-bottom" href="#" v-for="item in unread" :key="item.id">
                    <div class="notifyimg bg-pink">
                        <i class="la la-file-alt text-white"></i>
                    </div>
                    <div class="mr-3">
                        <h5 class="notification-label mb-1">New files available</h5>
                        <div class="notification-subtext">10 hour ago</div>
                    </div>
                    <div class="mr-auto">
                        <i class="las la-angle-left text-left text-muted"></i>
                    </div>
                </a>
                <a class="d-flex p-3" href="#">
                    <div class="notifyimg bg-purple">
                        <i class="la la-gem text-white"></i>
                    </div>
                    <div class="mr-3">
                        <h5 class="notification-label mb-1">Updates Available</h5>
                        <div class="notification-subtext">2 days ago</div>
                    </div>
                    <div class="mr-auto">
                        <i class="las la-angle-left text-left text-muted"></i>
                    </div>
                </a>
                <a class="d-flex p-3 border-bottom" href="#">
                    <div class="notifyimg bg-success">
                        <i class="la la-shopping-basket text-white"></i>
                    </div>
                    <div class="mr-3">
                        <h5 class="notification-label mb-1">New Order Received</h5>
                        <div class="notification-subtext">1 hour ago</div>
                    </div>
                    <div class="mr-auto">
                        <i class="las la-angle-left text-left text-muted"></i>
                    </div>
                </a>
                <a class="d-flex p-3 border-bottom" href="#">
                    <div class="notifyimg bg-warning">
                        <i class="la la-envelope-open text-white"></i>
                    </div>
                    <div class="mr-3">
                        <h5 class="notification-label mb-1">New review received</h5>
                        <div class="notification-subtext">1 day ago</div>
                    </div>
                    <div class="mr-auto">
                        <i class="las la-angle-left text-left text-muted"></i>
                    </div>
                </a>
                <a class="d-flex p-3 border-bottom" href="#">
                    <div class="notifyimg bg-danger">
                        <i class="la la-user-check text-white"></i>
                    </div>
                    <div class="mr-3">
                        <h5 class="notification-label mb-1">22 verified registrations</h5>
                        <div class="notification-subtext">2 hour ago</div>
                    </div>
                    <div class="mr-auto">
                        <i class="las la-angle-left text-left text-muted"></i>
                    </div>
                </a>
                <a class="d-flex p-3 border-bottom" href="#">
                    <div class="notifyimg bg-primary">
                        <i class="la la-check-circle text-white"></i>
                    </div>
                    <div class="mr-3">
                        <h5 class="notification-label mb-1">Project has been approved</h5>
                        <div class="notification-subtext">4 hour ago</div>
                    </div>
                    <div class="mr-auto">
                        <i class="las la-angle-left text-left text-muted"></i>
                    </div>
                </a>
            </div>
            <div class="dropdown-footer">
                <a href="">VIEW ALL</a>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data: function () {
        return {
            read: {},
            unread: {},
            unreadCount: 0
        }
    },
    created: function () {
        this.getNotification();
        let userId = $('meta[name="userId"]').attr('content');
        Echo.private('App.Models.User.'+id)
            .notification((notification) => {
                this.unread.unshift(notification);
                this.unreadCount++;
            });
    },
    methods: {
        getNotification(){
            axios.get('notification/get').then(res => {
                this.read           =  res.data.read;
                this.unread         =  res.data.unread;
                this.unreadCount    =  res.data.unread.length;
            }).catch(error => Eception.handle(error))
        },
        readNotification(){
            axios.post('notification/read',{id:notification.id}).then(res =>{
                this.unread.splice(notification,1);
                this.read.push(notification);
                this.unreadCount--;
            })
        }
    }
}
</script>
