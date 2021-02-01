<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sample Form</title>
	<link rel="stylesheet" href="assets/style.css">
</head>
<body>

	<div id="app">

		<div class="form-wrapper">

			<h1>Форма обратной связи</h1>

			<p class="form-description">Заполните пожалуйста форму для соединения с нужным менеджером в нужном регионе. <a href="https://www.magistral-nn.ru/company/career/b1/" target="_blank">Подробнее...</a></p>

			<form v-if="!send" method="post" class="sample-form" :class="{ blocked: blocked }">

				<div class="sample-form__row select">
					<label class="sample-form__label"><span class="step-number" :class="{agree: inp.region.value, wrong: inp.region.wrong}">1</span>{{inp.region.name}}<span class="required">*</span></label>
					<div class="sample-form__select">

						<v-select :placeholder="inp.region.placeholder" label="name" :options="inp.region.list" @input="setRegion">
							<div slot="no-options">значений не найдено...</div>
						</v-select>

						<span v-if="inp.region.wrong" v-html="inp.region.wrong" class="sample-form__wrong"></span>

					</div>

				</div>

				<div v-if="inp.department.list" class="sample-form__row select">
					<label class="sample-form__label"><span class="step-number" :class="{agree: inp.department.value, wrong: inp.department.wrong}">2</span>{{inp.department.name}}<span class="required">*</span></label>
					<div class="sample-form__select">
						<v-select :placeholder="inp.department.placeholder" label="name" :options="inp.department.list" @input="setDepartment">
							<div slot="no-options">значений не найдено...</div>
						</v-select>

						<span v-if="inp.department.wrong" v-html="inp.department.wrong" class="sample-form__wrong"></span>

					</div>
				</div>
				
				<div v-if="inp.department.value" class="sample-form__row">

					<label class="sample-form__label"><span class="step-number" :class="{agree: phoneValid(), wrong: inp.phone.wrong}">3</span>{{inp.phone.name}}<span class="required">*</span></label>

					<div class="sample-form__input">
						<input v-mask="inp.phone.mask" type="tel" class="inpt" :placeholder="inp.phone.placeholder" v-model="inp.phone.value">

						<span v-if="inp.phone.wrong" v-html="inp.phone.wrong" class="sample-form__wrong"></span>

					</div>

				</div>

				<p class="required-description"><span class="required">*</span> - Звездочкой помечены обязательные поля.</p>

				<div class="simple-form__button" v-if="formValid() === true">
					<input type="submit" class="btn" @click="formSubmit" value="Отправить">
				</div>
				

			</form>

			<form-send v-if="responce" :res="responce" :inp="inp"></form-send>

		</div>

	</div>

	<script src="assets/vue.min.js"></script>
	<script src="assets/vue-select.js"></script>
	<script src="assets/v-mask.min.js"></script>
	<script src="assets/axios.min.js"></script>
	<script src="assets/script.js"></script>

</body>
</html>