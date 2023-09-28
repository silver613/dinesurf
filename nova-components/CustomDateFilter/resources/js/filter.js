import Filter from './components/Filter'

Nova.booting((app, store) => {
  app.component('custom-date-filter', Filter)
})
