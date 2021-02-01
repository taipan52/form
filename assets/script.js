//маска для телефона
Vue.use(VueMask.VueMaskPlugin);


//исходные данные
const sampleForm = {

	inp: {
		region: {
			name: 'Регион РФ',
			placeholder: 'Выберите ваш регион',
			list: [],//для инициализации select компонента
			value: false,
			wrong: false
		},

		department: {
			name: 'Департамент',
			placeholder: 'Выберите департамент',
			list: false,
			value: false,
			wrong: false,
		},

		phone: {
			name: 'Номер телефона',
			mask: '+7(###)###-##-##',
			placeholder: '+7(000)000-00-00',
			value: '', //для инициализации маски
			wrong: false,
		},		
	},

	send: false,
	blocked: false,
	responce: false,


}

new Vue({
	el: '#app',
	data: sampleForm,

	mounted() {

		//получение регионов
		let vm = this;

		axios
			.get(
				'ajax/form_getter.php',
				{params: {
					action: 'init',
				}}
			)
			.then(function(res) {

				if(res.data.success) {
					vm.inp.region.list = res.data.list;
				}
				else {
					vm.inp.region.wrong = 'Не удалось получить список Регионов';
				}
				
			})
			.catch(function(err) {
				console.log(err);
			});
	},

	methods: {

		//установка региона
		setRegion(value) {
			this.inp.region.value = value;
			this.inp.department.list = false;
			this.inp.department.value = false;
			this.resetWrong();

			//получение департаментов
			let vm = this;

			if(this.inp.region.value) {

				axios
					.get(
						'ajax/form_getter.php',
						{params: {
							action: 'change',
							region_id: vm.inp.region.value.id,
						}}
					)
					.then(function(res) {

						if(res.data.success) {

							if(res.data.list !== null && res.data.list.length > 0) {
								vm.inp.region.wrong = false;
								vm.inp.department.list = res.data.list;
							}
							else {
								vm.inp.region.wrong = 'У региона <strong>'+vm.inp.region.value.name+'</strong> отсутствуют департаменты';
							}						

						}
						else {
							vm.inp.department.wrong = 'Не удалось получить список Департаментов';
						}



					})
					.catch(function(err) {
						console.log(err);
					});

			}

		},

		//установка департамента
		setDepartment(value) {
			this.inp.department.wrong = false;
			this.inp.department.value = value;
		},


		phoneValid() {

			if(this.inp.phone.value.length === 16) return true;
			return false;
		},

		//проверка заполнения полей
		formValid() {

			if(
				this.inp.region.value &&
				this.inp.department.value &&
				this.inp.phone.value.length === 16) {
				return true;
			}

			return false;

		},

		//сброс ошибок
		resetWrong() {

			for (let prop in this.inp) {
				this.inp[prop].wrong = false;
			}

		},

		//отправка формы
		formSubmit(event) {

			event.preventDefault();
			this.blocked = true;

			let vm = this;

			if(this.formValid()) {

				const params = new URLSearchParams();
				params.append('region', vm.inp.region.value.name);
				params.append('department', vm.inp.department.value.name);
				params.append('phone', vm.inp.phone.value);

				axios
					.post(
						'ajax/callback.php',
						params
					)
					.then(function(res) {

						//сброс ошибок
						vm.resetWrong();

						if(res.data.send !== undefined) {

							if(res.data.errors) {
								//отображение ошибки
								res.data.errors.forEach(function(item) {
									vm.inp[item.code].wrong = item.text;
								});
							}
							else {
								//форма успешно отправлена
								vm.responce = res.data;
								vm.send = true;
							}
							
						}
						else {
							//прочие ошибки
							vm.responce = {
								errors: true,
								errorsText: res.data
							};
						}



					})
					.catch(function(err) {
						console.log(err);
					})
					.finally(function(){
						vm.blocked = false;
					});

			}

		}
	}
})

//компонент селектора
Vue.component('v-select', VueSelect.VueSelect);

//компонент вывода результатов
Vue.component('form-send', {
	props: {
		res: false,
		inp: false,
	},
  	template: 
  	'<div class="smple-form__result">\
  		<div v-if="res.errors" class="form-error">\
  			<h4>ОШИБКА ОТПРАВКИ ФОРМЫ!</h4>\
  			<div v-html="res.errorsText"></div>\
  		</div>\
  		<div v-if="!res.errors" class="form-send">\
  			<h4>ФОРМА УСПЕШНО ОТПРАВЛЕНА!</h4>\
  			Список полученных полей<br<br>\
  			<ul>\
  				<li v-for="item in res.corrects"><b>{{ inp[item.code].name }}:</b> {{item.value}}</li>\
  			</ul>\
  		</div>\
  	</div>'
})