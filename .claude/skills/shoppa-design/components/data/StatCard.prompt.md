Dashboard KPI tile — label, tinted icon chip, big value, optional month-over-month trend. Used 4-across on buyer/vendor/admin dashboards.

```jsx
<StatCard label="Active orders" value="3" icon="box" iconColor="blue" />
<StatCard label="Verified devices" value="128" icon="shield" iconColor="emerald" trend="+12%" trendDir="up" />
<StatCard label="Balance (KSh)" value="84,200.00" icon="store" iconColor="purple" />
```

Icon tints: `emerald | blue | amber | red | purple`. Trend dir sets the colour: up = emerald, down = red, neutral = stone.
