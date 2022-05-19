<div x-data="availability">
    <form class="flex flex-col" @submit.prevent="addBooking">
        <div class="w-full p-4">
            <label for="guests" class="inline-block w-24">Guests</label>
            <select name="bookingGuests" id="guests" x-model="guests" class="w-64" @change="checkAvailability()">
                @for ($i = 1; $i <= $tables*2; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="w-full p-4">
            <label for="date" class="inline-block w-24">Date</label>
            <input type="date" name="bookingDate" x-model="date" id="date" class="w-64" @change="checkAvailability()">
        </div>
        <div class="w-full p-4">
            <label for="time" class="inline-block w-24">Timeslot</label>
            <select name="bookingTimeslot" id="time" x-model="timeslot" class="w-64" @change="setTimeslot($el)">
                <option value="notSelected">Select a timeslot</option>
                @foreach ($times as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full p-4">
            <div class="flex flex-row justify-between">
                @foreach ($dishes as $dish)
                    <div class="w-48 bg-gray-200 rounded-md shadow-md cursor-pointer" :class="dishSelected === $el.dataset.index ? 'border-red-300 border-2' : ''" data-index="{{ $loop->index }}" data-dish="{{ $dish->strMeal }}" @click="selectDish($el)">
                        <img src="{{ $dish->strMealThumb }}" class="w-full h-auto bg-cover rounded-t-md" />
                        <p class="px-4 py-2 text-center">{{ $dish->strMeal }}</p>
                    </div>
                @endforeach
            </div>
            <div class="flex flex-row justify-between mt-8">
                @foreach ($drinks as $drink)
                    <div class="w-48 bg-gray-200 rounded-md shadow-md cursor-pointer" :class="drinkSelected === $el.dataset.index ? 'border-red-300 border-2' : ''" data-index="{{ $loop->index }}" data-drink="{{ $drink->name }}" @click="selectDrink($el)">
                        <p class="px-4 py-2 text-center">{{ $drink->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="w-full p-4">
            <button type="submit" class="px-4 py-2 text-black bg-blue-400 rounded-md hover:bg-blue-700 hover:text-white">Make Booking</button>
        </div>
    </form>

    <div
        x-data="noticesHandler()"
        class="fixed inset-0 flex flex-col-reverse items-end justify-start w-screen h-screen"
        @notice.window="add($event.detail)"
        style="pointer-events:none">
        <template x-for="notice of notices" :key="notice.id">
            <div
                x-show="visible.includes(notice)"
                x-transition:enter="transition ease-in duration-200"
                x-transition:enter-start="transform opacity-0 translate-y-2"
                x-transition:enter-end="transform opacity-100"
                x-transition:leave="transition ease-out duration-500"
                x-transition:leave-start="transform translate-x-0 opacity-100"
                x-transition:leave-end="transform translate-x-full opacity-0"
                @click="remove(notice.id)"
                class="flex items-center justify-center w-56 h-16 mb-4 mr-6 text-lg font-bold text-white rounded shadow-lg cursor-pointer"
                :class="{
                    'bg-green-500': notice.type === 'success',
                    'bg-blue-500': notice.type === 'info',
                    'bg-orange-500': notice.type === 'warning',
                    'bg-red-500': notice.type === 'error',
                }"
                style="pointer-events:all"
                x-text="notice.text">
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('availability', () => ({
                guests: '',
                date: '',
                timeslot: '',
                dish: '',
                dishSelected: '',
                drink: '',
                drinkSelected: '',
                checkAvailability() {
                    fetch('/api/bookings/checkAvailability', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            guests: this.guests,
                            date: this.date,
                        })
                    })
                    .then(response => response.json())
                    .then(function(data){
                        document.querySelectorAll('#time > option').forEach(element => {
                            if(!data.data.includes(element.value)){
                                element.disabled = true;
                            } else {
                                element.disabled = false;
                            }
                        });
                    });
                },
                setTimeslot(element){
                    this.timeslot = element.value;
                },
                addBooking(){
                    fetch('/api/bookings/new', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            user: {{ Auth::user()->id }},
                            guests: parseInt(this.guests),
                            date: this.date,
                            timeslot: this.timeslot,
                            dish: this.dish,
                            drink: this.drink
                        })
                    })
                    .then(response => response.json())
                    .then(function(data){
                        $dispatch('notice', {type: 'success', text: '🔥 Success!'})
                    });
                },
                selectDish(element){
                    this.dish           = element.dataset.dish;
                    this.dishSelected   = element.dataset.index;
                },
                selectDrink(element){
                    this.drink          = element.dataset.drink;
                    this.drinkSelected  = element.dataset.index;
                }
            }))
        });
        function noticesHandler() {
            return {
                notices: [],
                visible: [],
                add(notice) {
                    notice.id = Date.now()
                    this.notices.push(notice)
                    this.fire(notice.id)
                },
                fire(id) {
                    this.visible.push(this.notices.find(notice => notice.id == id))
                    const timeShown = 2000 * this.visible.length
                    setTimeout(() => {
                        this.remove(id)
                    }, timeShown)
                },
                remove(id) {
                    const notice = this.visible.find(notice => notice.id == id)
                    const index = this.visible.indexOf(notice)
                    this.visible.splice(index, 1)
                },
            }
        }
    </script>
</div>
