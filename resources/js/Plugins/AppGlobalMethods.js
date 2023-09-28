import dayjs from "dayjs";
import numeral from "numeral";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css";

export default {
    install(Vue, options) {
        // 1. add global method or property
        Vue.mixin({
            data: function () {
                return {
                    priceRange: [
                        
                        {
                            from: 0,
                            to: 10000,
                            id: 1,
                        },
                        {
                            from: 10000,
                            to: 25000,
                            id: 2,
                        },
                        {
                            from: 25000,
                            to: 40000,
                            id: 3,
                        },
                        {
                            from: 40000,
                            to: "Above",
                            id: 4,
                        },
                    ],
                };;
            },
            methods: {
                checkVendorTime() {
                    // if (this.form.time && this.vendor) {
                    //     console.log(
                    //         this.form.time,
                    //         this.vendor.open_time,
                    //         this.vendor.close_time,
                    //         this.form.time < this.vendor.open_time,
                    //         this.form.time > this.vendor.close_time
                    //     );
                    //     if (this.vendor.open_time && this.vendor.close_time) {
                    //         if (this.form.time < this.vendor.open_time) {
                    //             this.form.time = null;
                    //         }
                    //         if (this.form.time > this.vendor.close_time) {
                    //             if (this.vendor.close_time == "00:00:00") {
                    //                 return true;
                    //             }
                    //             this.form.time = null;
                    //         }
                    //     }
                    // }

                    return;
                },
                setPhone(data) {
                    console.log("val: ", data.val);
                    if (data) {
                        if (data.val) {
                            if (this.form) {
                                if (data.model == "phone") {
                                    this.form.phone = data.val;
                                }
                                if (data.model == "phone_number") {
                                    this.form.phone_number = data.val;
                                }
                                if (data.model == "company_phone") {
                                    this.form.company_phone = data.val;
                                }
                            } else {
                                if (data.model == "phone") {
                                    this.phone = data.val;
                                }
                                if (data.model == "phone_number") {
                                    this.phone_number = data.val;
                                }
                                if (data.model == "company_phone") {
                                    this.company_phone = data.val;
                                }
                            }
                        }
                    }
                },
                unSetPhone(phone) {
                    var last10 = null;;
                    if (phone) {
                        last10 = phone.slice(-10);;
                    }
                    return last10;
                },
                numberWithCommas(x, code = null) {
                    if (x != null && x != "" && !isNaN(x)) {
                        var symbol = "";
                        if (code == "NGN") {
                            symbol = "₦";
                        }
                        if (code == "USD") {
                            symbol = "$";
                        }

                        if (code == "GHS") {
                            symbol = "¢";
                        }
                        
                        return symbol + numeral(x).format("0,0");
                    } else {
                        return x;
                    }
                },
                numberWithCommasDec(x, code = null) {
                    if (x != null && x != "") {
                        var symbol = "";
                        if (code == "NGN") {
                            symbol = "₦";
                        }
                        if (code == "USD") {
                            symbol = "$";
                        }
                        return symbol + numeral(x).format("0,0.00");
                    } else {
                        return x;
                    }
                },
                getUser(el) {
                    if (el.user) {
                        return (
                            (
                            el.user.first_name +
                           
                            " " +
                           
                            this.checkForNull(el.user.last_name)
                        )
                        );
                    }
                    if (el.guest) {
                        return (
                            el.guest.first_name +
                           
                            " " +
                           
                            this.checkForNull(el.guest.last_name)
                        );
                    }
                    return "";
                },
                openLocationDropdown() {
                    var dropdown = document.getElementById("locationSearchBtn");
                    if (dropdown) {
                        window.scrollTo({
                            top: 0,
                            behavior: "smooth",
                        });

                        dropdown.click();
                    }
                },
                checkForNull(val) {
                    if (val == null || val == "null" || val == undefined) {
                        return "";
                    }
                    return val;
                },
                logCurrentUrl(url) {
                    fetch(route("log-current-url"), {
                        method: "POST",
                        headers: {
                            Accept: "application/json",
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": this.$page.props.csrf_token,
                        },
                        body: JSON.stringify({
                            url: url,
                        }),
                    })
                        .then((result) => {
                            return result.json();
                        })
                        .then((result) => {
                            return;
                        })
                        .catch((err) => {
                            this.handleApiError(err);
                        });
                },
                getErrorMsg() {
                    toastr.error(err.response.data.message);
                    if (err.response.data.errors) {
                        this.showErrorMsg(err.response.data.errors);;
                    } else {
                        toastr.error(err.response.data.message);
                    }
                },
                handleApiError(err) {
                    if (err.response) {
                        toastr.error(err.response.data.message);
                        if (err.response.data.errors) {
                            return this.showErrorMsg(err.response.data.errors);;
                        } else {
                            if (
                                
                                err.response.data.message ==
                               
                                "CSRF token mismatch."
                            
                            ) {
                                window.location.reload();
                            }
                            return err.response.data.message;
                        }
                    } else {
                        toastr.error(err);
                    }
                },
                showErrorMsg(errors) {
                    var err = "";
                    Object.entries(errors).forEach(function (key, value) {
                        toastr.error(key);
                        err += key + "\r\n<br>";;
                    });
                    return err;;
                },
                returnErrorMsg(errors) {
                    var err = "";
                    Object.entries(errors).forEach(function (key, value) {
                        err +=
                           
                            '<span class="block"> ' +
                           
                            key[1][0] +
                            ' <i class="fas fa-exclamation-triangle"></i></span>';
                    });
                    // if (errors.email) {
                    //     err = err + '<span class="text-danger block"> ' + errors.email[0] +
                    //         ' <i class="fas fa-exclamation-triangle"></i></span>';
                    // }
                    // if (errors.username) {
                    //     err = err + '<span class="text-danger block"> ' + errors.username[0] +
                    //         ' <i class="fas fa-exclamation-triangle"></i></span>';
                    // }
                    // if (errors.password) {
                    //     err = err + '<span class="text-danger block"> ' + errors.password[0] +
                    //         ' <i class="fas fa-exclamation-triangle"></i></span>';
                    // }
                    // if (errors.name) {
                    //     err = err + '<span class="text-danger block"> ' + errors.name[0] +
                    //         ' <i class="fas fa-exclamation-triangle"></i></span>';
                    // }
                    // if (errors.class_id) {
                    //     err = err + '<span class="text-danger block"> ' + errors.class_id[0] +
                    //         ' <i class="fas fa-exclamation-triangle"></i></span>';
                    // }
                    // if (errors.phone) {
                    //     err = err + '<span class="text-danger block"> ' + errors.phone[0] +
                    //         ' <i class="fas fa-exclamation-triangle"></i></span>';
                    // }
                    return err;;
                },
                refreshToken() {
                    fetch(route("refresh-token"))
                        .then((result) => {
                            return result.json();
                        })
                        .then((result) => {
                            document
                                
                                .querySelector('meta[name="csrf-token"]')
                                
                                .setAttribute("content", result.token);
                        })
                        .catch((err) => {
                            this.handleApiError(err);
                        });
                },
                back() {
                    window.history.back();
                },
                runAction() {
                    var formData = new FormData();
                    formData.append("ids", this.backendActionData.ids);
                    formData.append(
                        
                        "allMatching",
                       
                        this.backendActionData.allMatching
                    
                    );
                    formData.append("action", this.backendActionData.action);
                    formData.append(
                        
                        "filters",
                       
                        JSON.stringify(this.backendActionData.filters)
                    
                    );
                    formData.append("subject", this.backendActionData.subject);
                    formData.append("message", this.backendActionData.message);
                    formData.append("banner", this.backendActionData.banner);
                    formData.append(
                        "action_model",
                        this.backendActionData.action_model
                    );

                    // this.$inertia.post(
                    //     route("vendors.run-action"), formData, {
                    //         fromImageUpload: true,
                    //     }
                    // );

                    this.responseSpin = true;
                    this.form.processing = true;
                    this.responseSuccess = null;
                    this.responseFail = null;

                    axios
                        .post(route("vendors.run-action"), formData)
                        .then((result) => {
                            result = result.data;
                            if (result.success) {
                                toastr.success(result.message);
                                this.responseFail = null;
                                var vm = this;
                                setTimeout(
                                    function () {
                                        vm.responseSpin = false;
                                        vm.responseMessage = null;
                                        vm.form.processing = false;
                                        vm.responseSuccess =
                                           
                                            result.message + "!";
                                        vm.sendSuccess = true;
                                    },
                                    500,
                                    vm,
                                    result
                                );
                            } else {
                                toastr.error("An Error Occured!");
                                this.form.processing = false;
                                this.responseSpin = false;
                                this.responseSuccess = null;
                                this.responseMessage = null;

                                if (result.errors) {
                                    this.responseFail = this.showErrorMsg(
                                        
                                        result.errors
                                    
                                    );
                                } else {
                                    if (
                                        
                                        result.message == "CSRF token mismatch."
                                    
                                    ) {
                                        window.location.reload();
                                    }
                                    this.responseFail = result.message;
                                }
                            }
                        })
                        .catch((err) => {
                            this.responseSpin = false;
                            this.form.processing = false;
                            this.responseSuccess = null;
                            this.responseFail = err;
                            this.responseMessage = null;
                            this.handleApiError(err);
                        });
                },
                blocked(email, blocklists = []) {
                    var isBlocked = false;
                    blocklists.forEach((el) => {
                        if (el.email == email) {
                            isBlocked = true;;
                        }
                    });
                    return isBlocked;;
                },
                scrollChildIntoView(parentId, childId) {
                    var parentElement = document.getElementById(parentId);
                    var childElement = document.getElementById(childId);
                    // console.log(childElement.offsetTop, parentElement.offsetTop);

                    parentElement.scrollTop =
                        childElement.offsetTop - parentElement.offsetTop;
                },
                addOrRemove(array, value) {
                    var index = array.indexOf(value);

                    if (index === -1) {
                        array.push(value);
                    } else {
                        array.splice(index, 1);
                    }

                    return array;;
                },
                setCityId(data) {
                    console.log("city id:", data);
                    // if (data) {
                    //     if (this.form) {
                    //         this.form.city_id = data
                    //     } else {
                    //         this.city_id = data
                    //     }
                    // }
                },
                nFormatter(n) {
                    if (n < 1e3) return this.numberWithCommas(n);
                    if (n >= 1e3 && n < 1e6) return +(n / 1e3).toFixed(1) + "K";
                    if (n >= 1e6 && n < 1e9) return +(n / 1e6).toFixed(1) + "M";
                    if (n >= 1e9 && n < 1e12)
                        return +(n / 1e9).toFixed(1) + "B";
                    if (n >= 1e12) return +(n / 1e12).toFixed(1) + "T";
                },
                pluralize(word, suffix = 's'){
                    return word + suffix
                },
                tipElements(els = []) {
                    els.forEach((el) => {
                        this.tipIt(el.id, el.content, el.placement ?? "top");
                    });
                },
                tipIt(id, content, placement = "top") {
                    tippy(id, {
                        content,
                        placement,
                    });
                },
                submitVendorForm(url, form, msg) {
                    this.form.processing = true;
                    this.responseSuccess = null;
                    this.responseFail = null;
                    this.responseMessage = msg;
                    axios
                        .post(url, {
                            ...form,
                        })
                        .then((result) => {
                            var result = result.data;
                            toastr.success(result.message);
                            this.responseMessage = null;
                            this.responseFail = null;
                            this.responseSuccess = result.message + "!";
                            var vm = this;
                            setTimeout(
                                function () {
                                    vm.form.processing = false;
                                    vm.regSuccess = true;
                                },
                                500,
                                vm,
                                result
                            );
                        })
                        .catch((err) => {
                            this.form.processing = false;
                            this.responseSuccess = null;
                            this.responseFail = this.handleApiError(err);
                            this.responseMessage = null;
                        });
                },
                formatDateTime(val) {
                    return dayjs(val).format("DD/MM/YYYY hh:mm A");
                },
            


                 getTimeAgo(date) {
                    var currentDate = new Date();  // Get the current date and time
                    var inputDate = new Date(date); // Convert the input date to a Date object
                  
                    // Calculate the time difference in milliseconds
                    var timeDifference = currentDate.getTime() - inputDate.getTime();
                  
                    // Calculate the number of days, hours, and minutes ago
                    var daysAgo = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                    var hoursAgo = Math.floor(timeDifference / (1000 * 60 * 60));
                    var minutesAgo = Math.floor(timeDifference / (1000 * 60));
                  
                    // Construct the time ago string
                    var timeAgoString = "";
                  
                    if (daysAgo > 0) {
                      timeAgoString += daysAgo === 1 ? "1 day ago" : daysAgo + " days ago";
                    } else if (hoursAgo > 0) {
                      timeAgoString += hoursAgo === 1 ? "1 hour ago" : hoursAgo + " hours ago";
                    } else if (minutesAgo > 0) {
                      timeAgoString += minutesAgo === 1 ? "1 minute ago" : minutesAgo + " minutes ago";
                    } else {
                      timeAgoString = "Just now";
                    }
                  
                    return timeAgoString;
                  }
                  
                
                  
                 
            },
        });
    },
};
